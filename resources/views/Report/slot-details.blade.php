@extends('layouts.master')
@section('title', 'Slot Details - Winning Report')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.reports.winningsslots') }}">Winning Slots Report</a>
                </li>
                <li class="breadcrumb-item active">Slot Details</li>
            </ol>
        </nav>

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.reports.winningsslots') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Back to Report
            </a>
        </div>

        <!-- Slot Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title mb-2">{{ $data['main_title'] ?? '-' }}</h3>
                        <p class="text-muted mb-2">
                            <strong>Type:</strong> {{ $data['title'] ?? '-' }} |
                            <strong>Short Title:</strong> {{ $data['short_title'] ?? '-' }}
                        </p>
                        <p class="text-muted mb-0">
                            <strong>Draw Date:</strong>
                            {{ !empty($data['draw_date']) ? Carbon\Carbon::parse($data['draw_date'])->format('d-m-Y') : '-' }} |
                            <strong>Draw Time:</strong> {{ $data['draw_time'] ?? '-' }} |
                            <strong>Booking Close:</strong>
                            {{ !empty($data['booking_close_time']) ? Carbon\Carbon::parse($data['booking_close_time'])->format('h:i A') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-primary" style="padding: 0.5rem 1rem; font-size: 0.95rem;">
                            {{ $data['summary']['total_tickets'] }} Total Tickets
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted mb-2">Total Tickets</h6>
                        <h4 class="card-text" style="color: #7367f0;">{{ $data['summary']['total_tickets'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted mb-2">Winners</h6>
                        <h4 class="card-text text-success">{{ $data['summary']['total_winners'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted mb-2">Losers</h6>
                        <h4 class="card-text text-danger">{{ $data['summary']['total_losers'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-muted mb-2">Win Rate</h6>
                        <h4 class="card-text" style="color: #28a745;">{{ $data['summary']['win_percentage'] }}%</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Winning Groups Section -->
        @if (!empty($data['winning_groups']))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Winning Groups</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Digit</th>
                                    <th>Group Name</th>
                                    <th>Color</th>
                                    <th>Ticket Amount</th>
                                    <th>Win Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['winning_groups'] as $group)
                                    <tr>
                                        <td>
                                            <strong>{{ $group['digit'] ?? '-' }}</strong>
                                        </td>
                                        <td>{{ $group['group_name'] ?? '-' }}</td>
                                        <td>
                                            @if (!empty($group['color']))
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge" style="background-color: {{ $group['color'] }};">
                                                        &nbsp;
                                                    </span>
                                                    <span>{{ $group['color'] }}</span>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>₹{{ number_format((float) ($group['ticket_amt'] ?? 0), 2) }}</td>
                                        <td>
                                            <strong class="text-success">
                                                ₹{{ number_format((float) ($group['win_amount'] ?? 0), 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Winners Section -->
        @php
            $winners = $data['customer_details']['winners'] ?? [];
            $losers = $data['customer_details']['losers'] ?? [];
        @endphp

        @if (!empty($winners))
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-check-circle me-2"></i>
                        Winning Tickets ({{ count($winners) }})
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        // Group winners by group_name
                        $groupedWinners = [];
                        foreach ($winners as $winner) {
                            $gName = strtoupper($winner['group_name'] ?? 'N/A');
                            $groupedWinners[$gName][] = [
                                'digit' => $winner['slot_digit'] ?? '-',
                                'quantity' => $winner['quantity'] ?? 0,
                            ];
                        }

                        // Sort group names: alphabetical groups first (sorted by length then alphabetically),
                        // then numeric groups sorted numerically.
                        uksort($groupedWinners, function ($a, $b) {
                            $aIsNumeric = is_numeric($a);
                            $bIsNumeric = is_numeric($b);

                            if ($aIsNumeric && !$bIsNumeric) {
                                return 1;
                            }
                            if (!$aIsNumeric && $bIsNumeric) {
                                return -1;
                            }
                            if ($aIsNumeric && $bIsNumeric) {
                                return (int)$a <=> (int)$b;
                            }
                            if (strlen($a) !== strlen($b)) {
                                return strlen($a) <=> strlen($b);
                            }
                            return strcmp($a, $b);
                        });

                        $maxRows = 0;
                        foreach ($groupedWinners as $gName => $items) {
                            $maxRows = max($maxRows, count($items));
                        }
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    @foreach ($groupedWinners as $gName => $items)
                                        <th colspan="2" class="text-center"><strong>{{ $gName }}</strong></th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($groupedWinners as $gName => $items)
                                        <th class="text-center text-muted small" style="font-size: 0.85rem;">N</th>
                                        <th class="text-center text-muted small" style="font-size: 0.85rem;">Q</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $maxRows; $i++)
                                    <tr>
                                        @foreach ($groupedWinners as $gName => $items)
                                            @if (isset($items[$i]))
                                                <td class="text-center">{{ $items[$i]['digit'] }}</td>
                                                <td class="text-center text-success"><strong>{{ $items[$i]['quantity'] }}</strong></td>
                                            @else
                                                <td class="bg-light text-center text-muted">-</td>
                                                <td class="bg-light text-center text-muted">-</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <!-- Winners Summary -->
                    <div class="alert alert-success mt-3 mb-0" role="alert">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Winning Tickets:</strong> {{ count($winners) }}
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Total Win Amount:</strong>
                                <span style="color: #28a745; font-size: 1.1rem; font-weight: bold;">
                                    ₹{{ number_format(array_sum(array_column($winners, 'win_amount')), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Winning Tickets</h5>
                </div>
                <div class="card-body text-center text-muted">
                    <p class="mb-0">No winning tickets for this slot.</p>
                </div>
            </div>
        @endif

        <!-- Losers Section -->
        @if (!empty($losers))
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-x-circle me-2"></i>
                        Losing Tickets ({{ count($losers) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Customer Name</th>
                                    <th>Mobile</th>
                                    <th>Customer ID</th>
                                    <th>Digit</th>
                                    <th>Group</th>
                                    <th>Quantity</th>
                                    <th>Ticket Amount</th>
                                    <th>Purchase Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($losers as $loser)
                                    <tr style="border-left: 4px solid #dc3545; opacity: 0.9;">
                                        <td>
                                            <strong class="text-primary">{{ $loser['ticket_number'] }}</strong>
                                        </td>
                                        <td>{{ $loser['customer_name'] }}</td>
                                        <td>
                                            <a href="tel:{{ $loser['customer_mobile'] }}" class="text-decoration-none">
                                                {{ $loser['customer_mobile'] }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ $loser['customer_id'] }}</span></td>
                                        <td>
                                            <span class="badge bg-warning">{{ $loser['slot_digit'] }}</span>
                                        </td>
                                        <td>{{ $loser['group_name'] }}</td>
                                        <td class="text-center">
                                            <strong>{{ $loser['quantity'] }}</strong>
                                        </td>
                                        <td>₹{{ number_format($loser['ticket_amount'], 2) }}</td>
                                        <td>
                                            <small class="text-muted">{{ $loser['booking_time'] ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">✗ LOST</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Losers Summary -->
                    <div class="alert alert-danger mt-3 mb-0" role="alert">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Losing Tickets:</strong> {{ count($losers) }}
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Total Amount Invested:</strong>
                                <span style="color: #dc3545; font-size: 1.1rem;">
                                    ₹{{ number_format(array_sum(array_column($losers, 'ticket_amount')), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Losing Tickets</h5>
                </div>
                <div class="card-body text-center text-muted">
                    <p class="mb-0">No losing tickets for this slot.</p>
                </div>
            </div>
        @endif

        <!-- Overall Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Overall Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="mb-2">
                            <strong>Total Tickets Sold:</strong>
                            <span class="badge bg-primary">{{ $data['summary']['total_tickets'] }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Win Rate:</strong>
                            <span class="badge bg-success">{{ $data['summary']['win_percentage'] }}%</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="mb-2">
                            <strong>Total Winnings Distributed:</strong>
                            <span class="text-success" style="font-size: 1.1rem; font-weight: bold;">
                                ₹{{ number_format($data['summary']['total_win_amount'], 2) }}
                            </span>
                        </p>
                        <p class="mb-2">
                            <strong>Total Investment:</strong>
                            <span class="text-muted" style="font-size: 1.1rem; font-weight: bold;">
                                ₹{{ number_format($data['summary']['total_invested'], 2) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
