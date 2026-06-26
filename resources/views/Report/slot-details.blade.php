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

        <!-- Back Button & Actions -->
        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('admin.reports.winningsslots') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Back to Report
            </a>
            <a href="{{ route('admin.reports.slot-tickets', $data['slot_id']) }}" class="btn btn-primary">
                <i class="bx bx-receipt"></i> View Tickets
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
            @php
                $hasThreeDigits = collect($data['winning_groups'])->contains('title', 3);
            @endphp
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
                                    @if ($hasThreeDigits)
                                        <th>First Prize</th>
                                        <th>Second Prize</th>
                                        <th>Third Prize</th>
                                    @endif
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
                                            @if ($group['title'] == 3)
                                                <span class="text-muted">-</span>
                                            @else
                                                <strong class="text-success">
                                                    ₹{{ number_format((float) ($group['win_amount'] ?? 0), 2) }}
                                                </strong>
                                            @endif
                                        </td>
                                        @if ($hasThreeDigits)
                                            @if ($group['title'] == 3)
                                                <td>
                                                    <strong class="text-success">
                                                        ₹{{ number_format((float) ($group['first_price'] ?? 0), 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        ₹{{ number_format((float) ($group['second_price'] ?? 0), 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        ₹{{ number_format((float) ($group['third_price'] ?? 0), 2) }}
                                                    </strong>
                                                </td>
                                            @else
                                                <td class="text-muted">-</td>
                                                <td class="text-muted">-</td>
                                                <td class="text-muted">-</td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
