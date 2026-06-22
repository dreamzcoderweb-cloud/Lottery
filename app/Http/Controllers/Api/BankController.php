<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\WalletRecharge;

class BankController extends Controller
{
    public function index()
    {
        $customer = auth()->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $bankAccounts = BankAccount::where('customer_id', $customer->customer_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Bank accounts retrieved successfully',
            'data' => $bankAccounts
        ]);
    }
   public function addBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'upi_id' => 'nullable|string|max:255',
        ]);

        $customer = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Create or Update Bank Account
        |--------------------------------------------------------------------------
        */

        $bankAccount = BankAccount::updateOrCreate(
            [
                'customer_id' => $customer->customer_id
            ],
            [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'account_holder_name' => $request->account_holder_name,
                'upi_id' => $request->upi_id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Update Wallet Recharge Table
        |--------------------------------------------------------------------------
        */

        WalletRecharge::updateOrCreate(
            ['customer_id' => $customer->customer_id],
            ['bank_acc_id' => $bankAccount->bank_account_id]
        );

        return response()->json([
            'status' => true,
            'message' => 'Bank account saved successfully',
            'bank_account_id' => $bankAccount->bank_account_id,
            'data' => $bankAccount
        ]);
    }
}
