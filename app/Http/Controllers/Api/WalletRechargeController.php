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

        // Retrieve all wallet transactions for calculations
        $allTransactions = WalletTransactions::where('customer_id', $customerId)->get();

        $winAmounts = [];
        $commissionAmounts = [];

        foreach ($allTransactions as $tx) {
            if ($tx->reference_no) {
                if (strpos($tx->reference_no, 'WIN-') === 0) {
                    $bookingId = substr($tx->reference_no, 4);
                    $winAmounts[$bookingId] = (float) $tx->amount;
                } elseif (strpos($tx->reference_no, 'COM-') === 0) {
                    $bookingId = substr($tx->reference_no, 4);
                    $commissionAmounts[$bookingId] = (float) $tx->amount;
                }
            }
        }

        $totalCommission = 0.0;
        $percentages = [];

        foreach ($commissionAmounts as $bookingId => $commAmt) {
            $totalCommission += $commAmt;
            $creditAmt = $winAmounts[$bookingId] ?? 0.0;
            $originalWin = $creditAmt + $commAmt;
            if ($originalWin > 0) {
                $percentages[] = ($commAmt / $originalWin) * 100;
            }
        }

        $count = count($percentages);
        $averageCommissionPercentage = $count > 0 ? (array_sum($percentages) / $count) : 0.0;

        // Filter and sort wallet transactions for history
        $targetRemarks = [
            'Wallet Recharge',
            'Lottery Booking Amount Deducted',
            'Slot winning amount credited',
            // 'Commission deducted from winnings',
        ];

        $transactions = $allTransactions->filter(function ($tx) use ($targetRemarks) {
            return in_array($tx->remarks, $targetRemarks);
        })->values();

        // Sort latest first
        $transactions = $transactions->sortByDesc('wallet_transaction_id')->values();

        // Calculate and attach commission_percentage and commission_amount on each transaction object
        $transactions->each(function ($tx) use ($winAmounts, $commissionAmounts) {
            $tx->commission_percentage = 0.00;
            $tx->commission_amount = 0.00;
            if ($tx->reference_no) {
                $bookingId = null;
                if (strpos($tx->reference_no, 'WIN-') === 0) {
                    $bookingId = substr($tx->reference_no, 4);
                } elseif (strpos($tx->reference_no, 'COM-') === 0) {
                    $bookingId = substr($tx->reference_no, 4);
                }

                if ($bookingId) {
                    $commAmt = $commissionAmounts[$bookingId] ?? 0.0;
                    $tx->commission_amount = round($commAmt, 2);
                    $creditAmt = $winAmounts[$bookingId] ?? 0.0;
                    $originalWin = $creditAmt + $commAmt;
                    if ($originalWin > 0) {
                        $tx->commission_percentage = round(($commAmt / $originalWin) * 100, 2);
                    }
                }
            }
        });

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
                'total_commission' => round($totalCommission, 2),
                'commission_percentage' => $count === 1 ? round($percentages[0], 2) : round($averageCommissionPercentage, 2),
                'average_commission_percentage' => round($averageCommissionPercentage, 2),
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Wallet data retrieved successfully',
            'wallet_balance' => $wallet?->balance ?? 0,
            'transactions' => $transactions,
            'recharge_requests' => $rechargeRequests,
            'total_commission' => round($totalCommission, 2),
            'commission_percentage' => $count === 1 ? round($percentages[0], 2) : round($averageCommissionPercentage, 2),
            'average_commission_percentage' => round($averageCommissionPercentage, 2),
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
