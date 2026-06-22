@extends('layouts.master')
@section('title', 'Wallet Withdrawals - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (session('success') || session('danger'))
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show mb-4" role="alert">
                <strong>{{ session('success') ? session('success') : session('danger') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="d-flex justify-content-between align-items-center p-3">
                <h5 class="card-header mb-0">Wallet Withdrawals</h5>
                <form method="get" action="{{ route('admin.withdrawals.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm" style="width: 180px;">
                        <option value="">All Status</option>
                        <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                </form>
            </div>

            <div class="table-responsive text-nowrap p-3">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Reference Code</th>
                            <th>Reference By Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($withdrawals as $w)
                            <tr>
                                <td>{{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $loop->iteration }}</td>
                                <td>{{ $w->customer->name ?? 'N/A' }}</td>
                                <td>{{ $w->customer->reference_code ?? '---' }}</td>
                                <td>{{ $w->customer->referredBy->name ?? '---' }}</td>
                                <td>{{ number_format((float) $w->amount, 2) }}</td>
                                <td>
                                    @php
                                        $badge = match((string) $w->status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-warning',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ strtoupper($w->status) }}</span>
                                </td>
                                <td>{{ $w->created_at ? \Carbon\Carbon::parse($w->created_at)->format('d-m-Y') : '' }}</td>
                                <td>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.withdrawals.show', $w->wallet_withdrawal_id) }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No withdrawal requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>
@endsection

