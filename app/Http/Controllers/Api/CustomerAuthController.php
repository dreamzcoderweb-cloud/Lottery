<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20', 'unique:customers,mobile'],
            'password' => ['required', 'string', 'min:6'],
            // Optional referral code of the person who referred this customer.
            'reference_code' => ['nullable', 'string', 'max:255', 'exists:customers,reference_code'],
        ]);

        return DB::transaction(function () use ($validated) {
            $referrer = null;
            if (! empty($validated['reference_code'])) {
                $referrer = Customer::where('reference_code', $validated['reference_code'])
                    ->lockForUpdate()
                    ->first();
            }

            $customerReferenceCode = $this->generateCustomerReferenceCode(prefix: 'KL', padLength: 3);

            $customer = Customer::create([
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'password' => Hash::make($validated['password']),
                'reference_code' => $customerReferenceCode,
                'referred_by_customer_id' => $referrer?->customer_id,
            ]);

            $token = $customer->createToken('customer-api')->plainTextToken;

            return response()->json([
                'message' => 'Registered successfully',
                'token' => $token,
                'customer' => $customer,
                'referred_by' => $referrer ? [
                    'customer_id' => $referrer->customer_id,
                    'name' => $referrer->name,
                    'mobile' => $referrer->mobile,
                    'reference_code' => $referrer->reference_code,
                ] : null,
            ], 201);
        });
    }

    private function generateCustomerReferenceCode(string $prefix = 'KL', int $padLength = 3): string
    {
        $latest = Customer::where('reference_code', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('customer_id')
            ->value('reference_code');

        $nextNumber = 1;
        if (is_string($latest) && str_starts_with($latest, $prefix)) {
            $numberPart = substr($latest, strlen($prefix));
            if ($numberPart !== '' && ctype_digit($numberPart)) {
                $nextNumber = (int) $numberPart + 1;
            }
        }

        for ($attempt = 0; $attempt < 50; $attempt++) {
            $code = $prefix . str_pad((string) $nextNumber, $padLength, '0', STR_PAD_LEFT);

            if (! Customer::where('reference_code', $code)->exists()) {
                return $code;
            }

            $nextNumber++;
        }

        throw new \RuntimeException('Unable to generate a unique customer reference code.');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'mobile' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ]);

        $customer = Customer::where('mobile', $validated['mobile'])->first();

        if (! $customer || ! Hash::check($validated['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'mobile' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $customer->createToken('customer-api')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'customer' => $customer,
        ]);
    }

   public function me(Request $request)
{
    $customer = $request->user();

    // current wallet balance
    $walletBalance = $customer->wallet?->balance ?? 0;

    // total recharge amount
    $totalRecharge = $customer->walletTransactions()
        ->where('type', 'credit')
        ->sum('amount');

    // total debit amount
    $totalDebit = $customer->walletTransactions()
        ->where('type', 'debit')
        ->sum('amount');

    return response()->json([
        'customer' => [
            'customer_id' => $customer->customer_id,
            'name' => $customer->name,
            'mobile' => $customer->mobile,
            'reference_code' => $customer->reference_code,

            // referred customer reference code
            'referred_by_reference_code' => $customer->referredBy?->reference_code,

            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
        ],

        'wallet' => [
            'current_balance' => $walletBalance,
            'total_recharge' => $totalRecharge,
            'total_debit' => $totalDebit,
        ]
    ]);
}


    public function profile(){

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
