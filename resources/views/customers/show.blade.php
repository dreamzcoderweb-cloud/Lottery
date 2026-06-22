@extends('layouts.master')
@section('title', 'Customer Details - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">Customer Details</h5>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-2">{{ $customer->name }}</h6>
                        <div class="mb-1"><strong>Mobile:</strong> {{ $customer->mobile }}</div>
                        <div class="mb-1"><strong>Reference:</strong> {{ $customer->reference_code }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Summary</h6>
                        <div class="row g-2">
                            <div class="col-md-4"><strong>Tickets:</strong> {{ $summary['tickets_count'] }}</div>
                            <div class="col-md-4"><strong>Total Qty:</strong> {{ $summary['total_qty'] }}</div>
                            <div class="col-md-4"><strong>Total Amount:</strong> {{ number_format($summary['total_amount'], 2) }}</div>
                            <div class="col-md-4"><strong>Winners:</strong> {{ $summary['winners_count'] }}</div>
                            <div class="col-md-4"><strong>Total Win:</strong> {{ number_format($summary['total_win_amount'], 2) }}</div>
                            <div class="col-md-4"><strong>Wallet Balance:</strong> {{ number_format($summary['wallet_balance'] ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

             <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Bank Details</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Bank</th>
                                    <th>Account No</th>
                                    <th>IFSC</th>
                                    <th>Holder</th>
                                    <th>UPI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bankAccounts as $acc)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $acc->bank_name }}</td>
                                        <td>{{ $acc->account_number }}</td>
                                        <td>{{ $acc->ifsc_code }}</td>
                                        <td>{{ $acc->account_holder_name }}</td>
                                        <td>{{ $acc->upi_id }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No bank details found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Wallet Recharge</h6>
                        <div class="text-muted small">Total: {{ number_format($summary['wallet_recharge_total'] ?? 0, 2) }}</div>
                    </div>
                    <div class="table-responsive">
                        <table id="walletRechargeTable" class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Balance</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($walletRecharges as $wr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ number_format((float) ($wr->balance ?? 0), 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($wr->created_at)->format('d-m-Y') }}</td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Wallet Transactions</h6>
                        <div class="text-muted small">Total: {{ number_format($summary['wallet_transaction_total'] ?? 0, 2) }}</div>
                    </div>
                    <div class="table-responsive">
                        <table id="walletTransactionsTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Remarks</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($walletTransactions as $wt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $wt->type }}</td>
                                        <td>{{ number_format((float) ($wt->amount ?? 0), 2) }}</td>
                                        <td>{{ $wt->payment_method }}</td>
                                        <td>{{ $wt->reference_no }}</td>
                                        <td>{{ $wt->remarks }}</td>
                                        <td>{{ \Carbon\Carbon::parse($wt->created_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Tickets & Winning Details</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="customer-ticket-winner" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Slot</th>
                                    <th>Draw Date</th>
                                    <th>Draw Time</th>
                                    <th>Title</th>
                                    <th>Digit</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th>Winner</th>
                                    <th>Win Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $b)
                                    @php
                                        $isWinner = (string) $b->is_winner === 'true' || (int) $b->is_winner === 1;
                                    @endphp
                                    <tr class="">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ optional($b->slot)->main_title }}
                                        </td>
                                       <td>
                                            {{ optional($b->slot)->draw_date
                                                ? \Carbon\Carbon::parse(optional($b->slot)->draw_date)->format('d-m-Y')
                                                : '' }}
                                        </td>
                                        <td>{{ optional($b->slot)->draw_time }}</td>
                                        @php
                                            $finalTitle = match((int) $b->title_id) {
                                                1 => 'Single Digit',
                                                2 => 'Double Digit',
                                                3 => 'Three Digit',
                                                4 => 'Four Digit',
                                                default => 'Unknown',
                                            };
                                        @endphp

                                        <td>{{ $finalTitle }}</td>
                                        <td>{{ $b->digits }}</td>
                                        <td>{{ $b->qty }}</td>
                                        <td>{{ number_format((float) $b->amount, 2) }}</td>
                                        <td>
                                            @if ($isWinner)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format((float) ($b->win_amount ?? 0), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-3">No tickets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
