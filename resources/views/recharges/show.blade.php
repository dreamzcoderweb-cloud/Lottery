@extends('layouts.master')
@section('title', 'Recharge Details - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">Recharge Request Details</h5>
            <a href="{{ route('admin.recharges.index') }}" class="btn btn-outline-secondary btn-sm">
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
            <!-- Details Column -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Recharge Request Info</h6>
                        <div class="mb-2"><strong>Request ID:</strong> #{{ $recharge->wallet_recharge_request_id }}</div>
                        <div class="mb-2"><strong>Customer ID:</strong> {{ $recharge->customer_id }}</div>
                        <div class="mb-2"><strong>Customer Name:</strong> {{ $recharge->customer->name ?? 'N/A' }}</div>
                        <div class="mb-2"><strong>Customer Mobile:</strong> {{ $recharge->customer->mobile ?? 'N/A' }}</div>
                        <div class="mb-2"><strong>Amount:</strong> {{ number_format((float) $recharge->amount, 2) }}</div>
                        <div class="mb-2"><strong>Payment Method:</strong> {{ strtoupper($recharge->payment_method) }}</div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            @php
                                $badge = match((string) $recharge->status) {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-warning',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ strtoupper($recharge->status) }}</span>
                        </div>
                        <div class="mb-2"><strong>Created:</strong> {{ $recharge->created_at ? \Carbon\Carbon::parse($recharge->created_at)->format('d-m-Y H:i') : '' }}</div>
                        <div class="mt-3">
                            <strong>Admin Remarks:</strong>
                            <div class="text-muted border p-2 rounded mt-1 bg-light">{{ $recharge->remarks ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image & Actions Column -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-3">Payment Proof Image</h6>
                        <div class="mb-3 text-center bg-light p-2 rounded border flex-grow-1 d-flex align-items-center justify-content-center" style="min-height: 250px;">
                            @if($recharge->payment_proof)
                                <a href="{{ asset('assets/img/payment_proofs/' . $recharge->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('assets/img/payment_proofs/' . $recharge->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded border" style="max-height: 350px; object-fit: contain;">
                                </a>
                            @else
                                <span class="text-muted">No proof uploaded</span>
                            @endif
                        </div>

                        <h6 class="card-title mb-3">Admin Action</h6>
                        @if ($recharge->status === 'pending')
                            @can('recharges.approve')
                                <form method="post" action="{{ route('admin.recharges.approve', $recharge->wallet_recharge_request_id) }}" class="mb-3">
                                    @csrf
                                    <label class="form-label fw-bold">Approve Remarks (optional)</label>
                                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <button type="submit" class="btn btn-success mt-2 w-100">Approve & Credit Balance</button>
                                </form>
                            @endcan

                            @can('recharges.reject')
                                <form method="post" action="{{ route('admin.recharges.reject', $recharge->wallet_recharge_request_id) }}">
                                    @csrf
                                    <label class="form-label fw-bold text-danger">Reject Remarks (required)</label>
                                    <textarea name="remarks" class="form-control" rows="2" required>{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <button type="submit" class="btn btn-danger mt-2 w-100">Reject Request</button>
                                </form>
                            @endcan
                        @else
                            <div class="alert alert-info mb-0" role="alert">
                                This recharge request has already been <strong>{{ strtoupper($recharge->status) }}</strong>.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
