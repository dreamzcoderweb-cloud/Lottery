<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletRecharge;
use App\Models\WalletWithdrawal;
use App\Services\WalletWithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WalletWithdrawalController extends Controller
{
    public function __construct(private readonly WalletWithdrawalService $service)
    {
    }

    public function store(Request $request)
    {
        $settings = Config::get('wallet.withdrawal');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'otp_code' => [($settings['otp']['enabled'] ?? true) ? 'required' : 'nullable', 'string', 'min:4', 'max:10'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $customerId = (int) auth()->id();

        try {
            $this->service->validateBankOrUpi($customerId);

            $withdrawal = $this->service->createWithdrawal($customerId, $validated);

            return response()->json([
                'status' => true,
                'message' => 'Withdrawal request created.',
                'withdrawal' => $withdrawal,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function index()
    {
        $customerId = (int) auth()->id();

        $items = WalletWithdrawal::where('customer_id', $customerId)
            ->latest('wallet_withdrawal_id')
            ->paginate(20);

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal history.',
            'data' => $items,
        ]);
    }

    public function show(int $id)
    {
        $customerId = (int) auth()->id();

        $withdrawal = WalletWithdrawal::where('customer_id', $customerId)
            ->where('wallet_withdrawal_id', $id)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal details.',
            'withdrawal' => $withdrawal,
        ]);
    }

    public function sendOtp()
    {
        $customerId = (int) auth()->id();
        $res = $this->service->sendOtp($customerId);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent.',
            'data' => $res,
        ]);
    }

    public function validateBalance()
    {
        $customerId = (int) auth()->id();
        $wallet = WalletRecharge::where('customer_id', $customerId)->first();
        $settings = Config::get('wallet.withdrrawal');

        return response()->json([
            'status' => true,
            'wallet_balance' => (float) ($wallet?->balance ?? 0),
            'limits' => [
                'min' => (float) ($settings['min_amount'] ?? 0),
                'max' => (float) ($settings['max_amount'] ?? 0),
                'deduct_on' => (string) ($settings['deduct_on'] ?? 'request'),
            ],
            'otp_enabled' => (bool) ($settings['otp']['enabled'] ?? false),
        ]);
    }
}
