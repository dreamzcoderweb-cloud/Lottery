<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use App\Models\WalletWithdrawal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class WalletWithdrawalService
{
    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function createWithdrawal(int $customerId, array $data): WalletWithdrawal
    {
        return DB::transaction(function () use ($customerId, $data) {
            $settings = Config::get('wallet.withdrawal');

            $amount = (float) $data['amount'];
            $this->ensureLimits($amount, $settings);

            if (($settings['otp']['enabled'] ?? false) === true) {
                $this->verifyOtpForCustomer($customerId, (string) ($data['otp_code'] ?? ''));
            }

            // Validate available balance at request time (prevents creating requests without funds).
            $wallet = WalletRecharge::where('customer_id', $customerId)->lockForUpdate()->first();
            $balance = (float) ($wallet?->balance ?? 0);
            if ($balance < $amount) {
                throw ValidationException::withMessages(['balance' => 'Insufficient wallet balance.']);
            }

            $withdrawal = WalletWithdrawal::create([
                'customer_id' => $customerId,
                'amount' => $amount,
                'status' => 'pending',
                'remarks' => $data['remarks'] ?? null,
            ]);

            if (($settings['deduct_on'] ?? 'request') === 'request') {
                $this->debitWalletForWithdrawal($customerId, $withdrawal, 'Wallet Withdrawal Requested');
            }

            return $withdrawal->fresh();
        });
    }

    public function validateBankOrUpi(int $customerId): void
    {
        $bank = BankAccount::where('customer_id', $customerId)->first();

        if (!$bank ||( empty($bank->bank_name) && empty($bank->account_number) && empty($bank->ifsc_code) && empty($bank->upi_id))
        ) {
            throw new \Exception(
                'Bank account or UPI details not found. Please add withdrawal details first.'
            );
        }
    }

    public function approve(int $withdrawalId, ?string $remark = null): WalletWithdrawal
    {
        return DB::transaction(function () use ($withdrawalId, $remark) {
            /** @var WalletWithdrawal $withdrawal */
            $withdrawal = WalletWithdrawal::lockForUpdate()->findOrFail($withdrawalId);

            if ($withdrawal->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Only pending withdrawals can be approved.',
                ]);
            }

            $settings = Config::get('wallet.withdrawal');
            if (($settings['deduct_on'] ?? 'request') === 'approval') {
                $this->debitWalletForWithdrawal((int) $withdrawal->customer_id, $withdrawal, 'Wallet Withdrawal Approved');
            }

            $withdrawal->status = 'approved';
            $withdrawal->remarks = $remark;
            $withdrawal->save();

            return $withdrawal->fresh();
        });
    }

    public function reject(int $withdrawalId, ?string $remark = null): WalletWithdrawal
    {
        return DB::transaction(function () use ($withdrawalId, $remark) {
            /** @var WalletWithdrawal $withdrawal */
            $withdrawal = WalletWithdrawal::lockForUpdate()->findOrFail($withdrawalId);

            if ($withdrawal->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Only pending withdrawals can be rejected.',
                ]);
            }

            $settings = Config::get('wallet.withdrawal');
            if (($settings['deduct_on'] ?? 'request') === 'request') {
                $this->refundWalletForWithdrawal((int) $withdrawal->customer_id, $withdrawal);
            }

            $withdrawal->status = 'rejected';
            $withdrawal->remarks = $remark;
            $withdrawal->save();

            return $withdrawal->fresh();
        });
    }

    public function sendOtp(int $customerId): array
    {
        $settings = Config::get('wallet.withdrawal.otp');
        if (($settings['enabled'] ?? false) !== true) {
            throw ValidationException::withMessages([
                'otp' => 'OTP is not enabled.',
            ]);
        }

        // Get customer with phone number
        $customer = Customer::findOrFail($customerId);
        if (empty($customer->mobile)) {
            throw ValidationException::withMessages([
                'mobile' => 'Mobile number not found for this customer.',
            ]);
        }

        // Generate OTP code
        $code = (string) random_int(100000, 999999);
        $ttl = (int) ($settings['ttl_seconds'] ?? 300);
        $cacheKey = $this->otpCacheKey($customerId);

        // Check if OTP send is already in progress (to prevent spam)
        // $existingOtp = Cache::get($cacheKey);
        // if ($existingOtp && !empty($existingOtp['sent_at'])) {
        //     $sentTime = Carbon::parse($existingOtp['sent_at']);
        //     $secondsElapsed = now()->diffInSeconds($sentTime);
        //     if ($secondsElapsed < 30) {
        //         throw ValidationException::withMessages([
        //             'otp' => 'Please wait before requesting a new OTP.',
        //         ]);
        //     }
        // }

        // Store OTP in cache
        Cache::put($cacheKey, [
            'hash' => Hash::make($code),
            'sent_at' => now()->toIso8601String(),
            'attempts' => 0,
        ], $ttl);

        // Send OTP via SMS
        $smsResult = $this->smsService->sendOtp($customer->mobile, $code);

        if (!$smsResult['success']) {
            // Remove the cached OTP if SMS sending failed
            Cache::forget($cacheKey);
            Log::error('Failed to send OTP via SMS', [
                'customer_id' => $customerId,
                'error' => $smsResult['message'],
            ]);
            throw ValidationException::withMessages([
                'otp' => 'Failed to send OTP. Please try again.',
            ]);
        }

        Log::info('OTP sent successfully', [
            'customer_id' => $customerId,
            'phone_number' => $this->maskPhoneNumber($customer->mobile),
        ]);

        return [
            'sent' => true,
            'otp_code' => config('app.debug') ? $code : null,
            'message' => 'OTP sent to registered mobile number.',
            'expires_in' => $ttl,
        ];
    }

    private function ensureLimits(float $amount, array $settings): void
    {
        $min = (float) ($settings['min_amount'] ?? 0);
        $max = (float) ($settings['max_amount'] ?? 0);

        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'Amount must be greater than 0.']);
        }
        if ($min > 0 && $amount < $min) {
            throw ValidationException::withMessages(['amount' => "Minimum withdrawal amount is {$min}."]);
        }
        if ($max > 0 && $amount > $max) {
            throw ValidationException::withMessages(['amount' => "Maximum withdrawal amount is {$max}."]);
        }
    }

    private function debitWalletForWithdrawal(int $customerId, WalletWithdrawal $withdrawal, string $remarks): void
    {
        $wallet = WalletRecharge::where('customer_id', $customerId)->lockForUpdate()->first();
        $balance = (float) ($wallet?->balance ?? 0);

        if ($balance < (float) $withdrawal->amount) {
            throw ValidationException::withMessages(['balance' => 'Insufficient wallet balance.']);
        }

        if (!$wallet) {
            $wallet = WalletRecharge::create(['customer_id' => $customerId, 'balance' => 0]);
        }

        $wallet->decrement('balance', (float) $withdrawal->amount);

        WalletTransactions::create([
            'customer_id' => $customerId,
            'type' => 'debit',
            'amount' => (float) $withdrawal->amount,
            'payment_method' => 'withdrawal',
            'reference_no' => 'WD-' . $withdrawal->wallet_withdrawal_id,
            'remarks' => $remarks,
        ]);
    }

    private function refundWalletForWithdrawal(int $customerId, WalletWithdrawal $withdrawal): void
    {
        $wallet = WalletRecharge::firstOrCreate(['customer_id' => $customerId], ['balance' => 0]);
        $wallet = WalletRecharge::where('customer_id', $customerId)->lockForUpdate()->first();

        $wallet->increment('balance', (float) $withdrawal->amount);

        WalletTransactions::create([
            'customer_id' => $customerId,
            'type' => 'credit',
            'amount' => (float) $withdrawal->amount,
            'payment_method' => 'withdrawal_refund',
            'reference_no' => 'WD-' . $withdrawal->wallet_withdrawal_id,
            'remarks' => 'Withdrawal Rejected - Amount Refunded',
        ]);
    }

    private function verifyOtpForCustomer(int $customerId, string $otp): void
    {
        $settings = Config::get('wallet.withdrawal.otp');
        $ttl = (int) ($settings['ttl_seconds'] ?? 300);

        if ($otp === '') {
            throw ValidationException::withMessages(['otp_code' => 'OTP code is required.']);
        }

        $cacheKey = $this->otpCacheKey($customerId);
        $cached = Cache::get($cacheKey);
        if (!$cached || !is_array($cached) || empty($cached['hash']) || empty($cached['sent_at'])) {
            throw ValidationException::withMessages(['otp_code' => 'OTP not sent.']);
        }

        if (Carbon::parse($cached['sent_at'])->addSeconds($ttl)->isPast()) {
            throw ValidationException::withMessages(['otp_code' => 'OTP expired.']);
        }

        if (!Hash::check($otp, (string) $cached['hash'])) {
            throw ValidationException::withMessages(['otp_code' => 'Invalid OTP.']);
        }

        Cache::forget($cacheKey);
    }

    private function otpCacheKey(int $customerId): string
    {
        return 'wallet_withdraw_otp:' . $customerId;
    }

    private function maskPhoneNumber(string $phoneNumber): string
    {
        $cleaned = preg_replace('/[\s\-\+]/', '', $phoneNumber);
        $length = strlen($cleaned);

        if ($length < 4) {
            return str_repeat('*', $length);
        }

        $visible = substr($cleaned, -4);
        return str_repeat('*', $length - 4) . $visible;
    }
}
