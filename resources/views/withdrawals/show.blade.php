@extends('layouts.master')
@section('title', 'Withdrawal Details - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">Withdrawal Details</h5>
            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>

        @if (session('success') || session('danger'))
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show mb-4" role="alert">
                <strong>{{ session('success') ? session('success') : session('danger') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Request</h6>
                        <div class="mb-1"><strong>ID:</strong> #{{ $withdrawal->wallet_withdrawal_id }}</div>
                        <div class="mb-1"><strong>Customer ID:</strong> {{ $withdrawal->customer_id }}</div>
                        <div class="mb-1"><strong>Amount:</strong> {{ number_format((float) $withdrawal->amount, 2) }}</div>
                        <div class="mb-1"><strong>Status:</strong> {{ strtoupper($withdrawal->status) }}</div>
                        <div class="mb-1"><strong>Created:</strong> {{ $withdrawal->created_at ? \Carbon\Carbon::parse($withdrawal->created_at)->format('d-m-Y H:i') : '' }}</div>
                        <div class="mt-3">
                            <strong>Remarks:</strong>
                            <div class="text-muted">{{ $withdrawal->remarks ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Admin Action</h6>

                        @if ($withdrawal->status === 'pending')
                            @can('withdrawals.approve')
                                <form method="post" action="{{ route('admin.withdrawals.approve', $withdrawal->wallet_withdrawal_id) }}" class="mb-3">
                                    @csrf
                                    <label class="form-label">Approve Remarks (optional)</label>
                                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <button type="submit" class="btn btn-success mt-2">Approve</button>
                                </form>
                            @endcan

                            @can('withdrawals.reject')
                                <form method="post" action="{{ route('admin.withdrawals.reject', $withdrawal->wallet_withdrawal_id) }}">
                                    @csrf
                                    <label class="form-label">Reject Remarks (required)</label>
                                    <textarea name="remarks" class="form-control" rows="2" required>{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <button type="submit" class="btn btn-danger mt-2">Reject</button>
                                </form>
                            @endcan
                        @else
                            <div class="text-muted">This request is already {{ $withdrawal->status }}.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

