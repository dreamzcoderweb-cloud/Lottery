<?php

namespace App\Http\Controllers;

use App\Models\WalletWithdrawal;
use App\Services\WalletWithdrawalService;
use Illuminate\Http\Request;

class WalletWithdrawalAdminController extends Controller
{
    public function __construct(private readonly WalletWithdrawalService $service)
    {
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = WalletWithdrawal::with('customer') // 🔥 important
        ->latest('wallet_withdrawal_id');

        if ($status) {
            $query->where('status', $status);
        }
        //dd($query->toSql(), $query->getBindings());
        $withdrawals = $query->paginate(20)->withQueryString();
        // dd($withdrawals);
        return view('withdrawals.view', compact('withdrawals', 'status'));
    }

    public function show(int $id)
    {
        $withdrawal = WalletWithdrawal::where('wallet_withdrawal_id', $id)->firstOrFail();

        return view('withdrawals.show', compact('withdrawal'));
    }

    public function approve(Request $request, int $id)
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->service->approve($id, $validated['remarks'] ?? null);

        return redirect()
            ->route('admin.withdrawals.show', $id)
            ->with('success', 'Withdrawal approved successfully.');
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'remarks' => ['required', 'string', 'max:2000'],
        ]);

        $this->service->reject($id, $validated['remarks']);

        return redirect()
            ->route('admin.withdrawals.show', $id)
            ->with('success', 'Withdrawal rejected successfully.');
    }
}

