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

        @php
            $winners = $data['customer_details']['winners'] ?? [];
            $losers = $data['customer_details']['losers'] ?? [];

            $allTickets = [];
            foreach ($winners as $winner) {
                $winner['is_winner_ticket'] = true;
                $allTickets[] = $winner;
            }
            foreach ($losers as $loser) {
                $loser['is_winner_ticket'] = false;
                $allTickets[] = $loser;
            }

            // Group all tickets by slot_items_id, group_name and ticket_amt, and aggregate quantities by booked_digits
            $groupedTickets = [];
            foreach ($allTickets as $ticket) {
                $gName = strtoupper($ticket['group_name'] ?? 'N/A');
                $ticketAmt = (int)($ticket['ticket_amt'] ?? 0);
                $slotItemId = $ticket['slot_items_id'] ?? '';
                // Include slot_items_id to prevent combining different slot items with same group name
                $gNameKey = $gName . ' (' . $ticketAmt . ') - ID:' . $slotItemId;
                $digit = $ticket['booked_digits'] ?? '-';
                $isWinner = $ticket['is_winner_ticket'] ?? false;

                if (!isset($groupedTickets[$gNameKey][$digit])) {
                    $groupedTickets[$gNameKey][$digit] = [
                        'quantity' => 0,
                        'is_winner' => $isWinner,
                    ];
                }
                $groupedTickets[$gNameKey][$digit]['quantity'] += $ticket['quantity'] ?? 0;
            }

            // Reformat groupedTickets to have the list style expected by the downstream code
            foreach ($groupedTickets as $gNameKey => $digits) {
                $items = [];
                foreach ($digits as $digit => $digitData) {
                    $items[] = [
                        'digit' => $digit,
                        'quantity' => $digitData['quantity'],
                        'is_winner' => $digitData['is_winner'],
                    ];
                }
                $groupedTickets[$gNameKey] = $items;
            }

            // Sort group names: alphabetical groups first, then numeric groups sorted numerically.
            // For the same group name, sort by ticket amount descending.
            uksort($groupedTickets, function ($a, $b) {
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
            foreach ($groupedTickets as $gName => $items) {
                $maxRows = max($maxRows, count($items));
            }
        @endphp

        <!-- Ticket Details Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="bx bx-receipt me-2"></i>
                    Ticket Details ({{ count($winners) + count($losers) }})
                </h5>
            </div>
            <div class="card-body mt-3">
                @if (!empty($groupedTickets))
                    <div class="table-responsive">
                        <table id="tickets-table" class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    @foreach ($groupedTickets as $gName => $items)
                                        @php
                                            // Extract display name without ID suffix
                                            $displayName = preg_replace('/ - ID:\d+$/', '', $gName);
                                        @endphp
                                        <th colspan="2" class="text-center"><strong>{{ $displayName }}</strong></th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($groupedTickets as $gName => $items)
                                        <th class="text-center text-muted small" style="font-size: 0.85rem;">N</th>
                                        <th class="text-center text-muted small" style="font-size: 0.85rem;">Q</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $maxRows; $i++)
                                    <tr>
                                        @foreach ($groupedTickets as $gName => $items)
                                            @if (isset($items[$i]))
                                                <td class="text-center">{{ $items[$i]['digit'] }}</td>
                                                <td class="text-center {{ $items[$i]['is_winner'] ? 'text-success' : 'text-danger' }}">
                                                    <strong>{{ $items[$i]['quantity'] }}</strong>
                                                </td>
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

                    <!-- Combined Summary Alert -->
                    <div class="alert alert-secondary mt-3 mb-0" role="alert">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <strong>Total Tickets:</strong> {{ count($winners) + count($losers) }}
                            </div>
                            <div class="col-md-3">
                                <strong>Total Winners:</strong> <span class="text-success">{{ count($winners) }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Total Losers:</strong> <span class="text-danger">{{ count($losers) }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Win Rate:</strong> {{ $data['summary']['win_percentage'] }}%
                            </div>
                            <div class="col-md-6">
                                <strong>Total Win Amount:</strong>
                                <span class="text-success" style="font-size: 1.1rem; font-weight: bold;">
                                    ₹{{ number_format(array_sum(array_column($winners, 'win_amount')), 2) }}
                                </span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <strong>Total Amount Invested:</strong>
                                <span class="text-danger" style="font-size: 1.1rem; font-weight: bold;">
                                    ₹{{ number_format(array_sum(array_column($losers, 'ticket_amount')), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <p class="mb-0">No tickets found for this slot.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
