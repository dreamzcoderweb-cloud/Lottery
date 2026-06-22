<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use App\Models\WalletRechargeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class WalletRechargeController extends Controller
{
    public function index()
    {
        $customerId = auth()->id();

        // wallet current balance
        $wallet = WalletRecharge::where('customer_id', $customerId)->first();

        // filtered wallet transactions history
        $transactions = WalletTransactions::where('customer_id', $customerId)

            ->whereIn('remarks', [
                'Wallet Recharge',
                'Lottery Booking Amount Deducted',
                'Slot winning amount credited',
            ])

            ->latest()
            ->get();

        // Retrieve all wallet recharge requests with their status
        $rechargeRequests = WalletRechargeRequest::where('customer_id', $customerId)
            ->select([
                'wallet_recharge_request_id',
                'customer_id',
                'amount',
                'payment_method',
                'payment_proof',
                'status',
                'remarks',
                'created_at',
                'updated_at'
            ])
            ->latest()
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->wallet_recharge_request_id,
                    'amount' => (float) $request->amount,
                    'payment_method' => $request->payment_method,
                    'payment_proof' => $request->payment_proof,
                    'status' => ucfirst($request->status), // Convert 'pending' to 'Pending', 'approved' to 'Approved', etc.
                    'remarks' => $request->remarks,
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                ];
            });

        if ($transactions->isEmpty() && $rechargeRequests->isEmpty()) {

            return response()->json([
                'status' => false,
                'message' => 'No wallet transactions or recharge requests found',
                'wallet_balance' => $wallet?->balance ?? 0,
                'transactions' => [],
                'recharge_requests' => [],
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Wallet data retrieved successfully',

            'wallet_balance' => $wallet?->balance ?? 0,

            'transactions' => $transactions,

            'recharge_requests' => $rechargeRequests,
        ]);
    }

   public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validate([
                'amount' => ['required', 'numeric', 'min:1'],
                'payment_method' => ['required', 'string'],
                'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            ]);

            $customerId = auth()->id();

            // Upload payment proof image
            $proofPath = null;

            if ($request->hasFile('payment_proof')) {
                $image = $request->file('payment_proof');
                $imageName = 'payment_proof_' . time() . '_' . $image->getClientOriginalName();

                $destinationPath = public_path('assets/img/payment_proofs');

                // Create directory if not exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $image->move($destinationPath, $imageName);

                $proofPath = $imageName;
            }

            // Create recharge request record
            $rechargeRequest = WalletRechargeRequest::create([
                'customer_id'    => $customerId,
                'amount'         => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_proof'  => $proofPath,
                'status'         => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Wallet recharge request submitted successfully and is pending approval',
                'recharge_request' => $rechargeRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
