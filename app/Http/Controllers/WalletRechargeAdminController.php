<?php

namespace App\Http\Controllers;

use App\Models\WalletRechargeRequest;
use App\Models\WalletRecharge;
use App\Models\WalletTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletRechargeAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = WalletRechargeRequest::with('customer')
            ->latest('wallet_recharge_request_id');

        if ($status) {
            $query->where('status', $status);
        }

        $recharges = $query->paginate(20)->withQueryString();

        return view('recharges.view', compact('recharges', 'status'));
    }

    public function show(int $id)
    {
        $recharge = WalletRechargeRequest::where('wallet_recharge_request_id', $id)->firstOrFail();

        return view('recharges.show', compact('recharge'));
    }

    public function approve(Request $request, int $id)
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $recharge = WalletRechargeRequest::lockForUpdate()->findOrFail($id);

                if ($recharge->status !== 'pending') {
                    throw new \Exception('Only pending recharge requests can be approved.');
                }

                // update status and remarks
                $recharge->status = 'approved';
                $recharge->remarks = $validated['remarks'] ?? null;
                $recharge->save();

                // update wallet balance in WalletRecharge table (firstOrCreate if not exists)
                $wallet = WalletRecharge::firstOrCreate(
                    ['customer_id' => $recharge->customer_id],
                    ['balance' => 0]
                );
                $wallet->increment('balance', $recharge->amount);

                // create a corresponding WalletTransactions record
                WalletTransactions::create([
                    'customer_id'    => $recharge->customer_id,
                    'type'           => 'credit',
                    'amount'         => $recharge->amount,
                    'payment_method' => $recharge->payment_method,
                    'reference_no'   => 'RC-' . $recharge->wallet_recharge_request_id,
                    'remarks'        => 'Wallet Recharge',
                ]);
            });

            return redirect()
                ->route('admin.recharges.show', $id)
                ->with('success', 'Wallet recharge request approved successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.recharges.show', $id)
                ->with('danger', 'Error: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:2000'],
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $recharge = WalletRechargeRequest::lockForUpdate()->findOrFail($id);

                if ($recharge->status !== 'pending') {
                    throw new \Exception('Only pending recharge requests can be rejected.');
                }

                // update status and remarks
                $recharge->status = 'rejected';
                $recharge->remarks = $validated['remarks'];
                $recharge->save();
            });

            return redirect()
                ->route('admin.recharges.show', $id)
                ->with('success', 'Wallet recharge request rejected successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.recharges.show', $id)
                ->with('danger', 'Error: ' . $e->getMessage());
        }
    }
}
