@extends('layouts.master')
@section('title', 'Wallet Recharges - Super Admin')
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
                <h5 class="card-header mb-0">Wallet Recharges</h5>
                <form method="get" action="{{ route('admin.recharges.index') }}" class="d-flex gap-2">
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
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($recharges as $r)
                            <tr>
                                <td>{{ ($recharges->currentPage() - 1) * $recharges->perPage() + $loop->iteration }}</td>
                                <td>{{ $r->customer->name ?? 'N/A' }}</td>
                                <td>{{ $r->customer->reference_code ?? '---' }}</td>
                                <td>{{ number_format((float) $r->amount, 2) }}</td>
                                <td>{{ strtoupper($r->payment_method) }}</td>
                                <td>
                                    @php
                                        $badge = match((string) $r->status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-warning',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ strtoupper($r->status) }}</span>
                                </td>
                                <td>{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d-m-Y') : '' }}</td>
                                <td>
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.recharges.show', $r->wallet_recharge_request_id) }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No recharge requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $recharges->links() }}
            </div>
        </div>
    </div>
@endsection
