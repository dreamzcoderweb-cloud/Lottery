@extends('layouts.master')
@section('title', 'Slot Ticket Details - Winning Report')
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
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.reports.slot-details', $data['slot_id']) }}">Slot Details</a>
                </li>
                <li class="breadcrumb-item active">Ticket Details</li>
            </ol>
        </nav>

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.reports.slot-details', $data['slot_id']) }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Back to Slot Details
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

                        // Group winners by group_name and ticket_amt, and aggregate quantities by slot_digit
                        $groupedWinners = [];
                        foreach ($winners as $winner) {
                            $gName = strtoupper($winner['group_name'] ?? 'N/A');
                            $ticketAmt = (int)($winner['ticket_amt'] ?? 0);
                            $gNameKey = $gName . ' (' . $ticketAmt . ')';
                            $digit = $winner['slot_digit'] ?? '-';

                            if (!isset($groupedWinners[$gNameKey][$digit])) {
                                $groupedWinners[$gNameKey][$digit] = [
                                    'quantity' => 0,
                                ];
                            }
                            $groupedWinners[$gNameKey][$digit]['quantity'] += $winner['quantity'] ?? 0;
                        }

                        // Reformat groupedWinners to have the list style expected by the downstream code
                        foreach ($groupedWinners as $gNameKey => $digits) {
                            $items = [];
                            foreach ($digits as $digit => $digitData) {
                                $items[] = [
                                    'digit' => $digit,
                                    'quantity' => $digitData['quantity'],
                                ];
                            }
                            $groupedWinners[$gNameKey] = $items;
                        }

                        // Sort group names: alphabetical groups first, then numeric groups sorted numerically.
                        // For the same group name, sort by ticket amount descending (e.g. ABC (300) before ABC (200)).
                        uksort($groupedWinners, function ($a, $b) {
                            // Extract group name and ticket amount from keys like "ABC (300)"
                            preg_match('/^([^\s(]+)(?:\s*\((\d+)\))?$/', $a, $matchesA);
                            preg_match('/^([^\s(]+)(?:\s*\((\d+)\))?$/', $b, $matchesB);

                            $gNameA = $matchesA[1] ?? $a;
                            $amtA = isset($matchesA[2]) ? (int)$matchesA[2] : 0;

                            $gNameB = $matchesB[1] ?? $b;
                            $amtB = isset($matchesB[2]) ? (int)$matchesB[2] : 0;

                            if ($gNameA !== $gNameB) {
                                $aIsNumeric = is_numeric($gNameA);
                                $bIsNumeric = is_numeric($gNameB);

                                if ($aIsNumeric && !$bIsNumeric) {
                                    return 1;
                                }
                                if (!$aIsNumeric && $bIsNumeric) {
                                    return -1;
                                }
                                if ($aIsNumeric && $bIsNumeric) {
                                    return (int)$gNameA <=> (int)$gNameB;
                                }
                                if (strlen($gNameA) !== strlen($gNameB)) {
                                    return strlen($gNameA) <=> strlen($gNameB);
                                }
                                return strcmp($gNameA, $gNameB);
                            }

                            // Sort by ticket amount descending when group name is identical
                            return $amtB <=> $amtA;
                        });

                        $maxRows = 0;
                        foreach ($groupedWinners as $gName => $items) {
                            $maxRows = max($maxRows, count($items));
                        }
                    @endphp

                    <div class="table-responsive">
                        <table id="winning-tickets-table" class="table table-bordered table-hover align-middle">
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
                        Lose Tickets ({{ count($losers) }})
                    </h5>
                </div>
                <div class="card-body">
                    @php

                        // Group losers by group_name and ticket_amt, and aggregate quantities by slot_digit
                        $groupedLosers = [];
                        foreach ($losers as $loser) {
                            $gName = strtoupper($loser['group_name'] ?? 'N/A');
                            $ticketAmt = (int)($loser['ticket_amt'] ?? 0);
                            $gNameKey = $gName . ' (' . $ticketAmt . ')';
                            $digit = $loser['slot_digit'] ?? '-';

                            if (!isset($groupedLosers[$gNameKey][$digit])) {
                                $groupedLosers[$gNameKey][$digit] = [
                                    'quantity' => 0,
                                ];
                            }
                            $groupedLosers[$gNameKey][$digit]['quantity'] += $loser['quantity'] ?? 0;
                        }

                        // Reformat groupedLosers to have the list style expected by the downstream code
                        foreach ($groupedLosers as $gNameKey => $digits) {
                            $items = [];
                            foreach ($digits as $digit => $digitData) {
                                $items[] = [
                                    'digit' => $digit,
                                    'quantity' => $digitData['quantity'],
                                ];
                            }
                            $groupedLosers[$gNameKey] = $items;
                        }

                        // Sort group names: alphabetical groups first, then numeric groups sorted numerically.
                        // For the same group name, sort by ticket amount descending (e.g. ABC (300) before ABC (200)).
                        uksort($groupedLosers, function ($a, $b) {
                            // Extract group name and ticket amount from keys like "ABC (300)"
                            preg_match('/^([^\s(]+)(?:\s*\((\d+)\))?$/', $a, $matchesA);
                            preg_match('/^([^\s(]+)(?:\s*\((\d+)\))?$/', $b, $matchesB);

                            $gNameA = $matchesA[1] ?? $a;
                            $amtA = isset($matchesA[2]) ? (int)$matchesA[2] : 0;

                            $gNameB = $matchesB[1] ?? $b;
                            $amtB = isset($matchesB[2]) ? (int)$matchesB[2] : 0;

                            if ($gNameA !== $gNameB) {
                                $aIsNumeric = is_numeric($gNameA);
                                $bIsNumeric = is_numeric($gNameB);

                                if ($aIsNumeric && !$bIsNumeric) {
                                    return 1;
                                }
                                if (!$aIsNumeric && $bIsNumeric) {
                                    return -1;
                                }
                                if ($aIsNumeric && $bIsNumeric) {
                                    return (int)$gNameA <=> (int)$gNameB;
                                }
                                if (strlen($gNameA) !== strlen($gNameB)) {
                                    return strlen($gNameA) <=> strlen($gNameB);
                                }
                                return strcmp($gNameA, $gNameB);
                            }

                            // Sort by ticket amount descending when group name is identical
                            return $amtB <=> $amtA;
                        });

                        $maxRowsLosers = 0;
                        foreach ($groupedLosers as $gName => $items) {
                            $maxRowsLosers = max($maxRowsLosers, count($items));
                        }
                    @endphp

                    <div class="table-responsive">
                        <table id="lose-tickets-table" class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    @foreach ($groupedLosers as $gName => $items)
                                        <th colspan="2" class="text-center"><strong>{{ $gName }}</strong></th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($groupedLosers as $gName => $items)
                                        <th class="text-center text-muted small" style="font-size: 0.85rem;">N</th>
                                        <th class="text-center text-muted small" style="font-size: 0.85rem;">Q</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $maxRowsLosers; $i++)
                                    <tr>
                                        @foreach ($groupedLosers as $gName => $items)
                                            @if (isset($items[$i]))
                                                <td class="text-center">{{ $items[$i]['digit'] }}</td>
                                                <td class="text-center text-danger"><strong>{{ $items[$i]['quantity'] }}</strong></td>
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

                    <!-- Losers Summary -->
                    <div class="alert alert-danger mt-3 mb-0" role="alert">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Losing Tickets:</strong> {{ count($losers) }}
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Total Amount Invested:</strong>
                                <span style="color: #dc3545; font-size: 1.1rem; font-weight: bold;">
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
                    <h5 class="card-title mb-0">Lose Tickets</h5>
                </div>
                <div class="card-body text-center text-muted">
                    <p class="mb-0">No losing tickets for this slot.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
