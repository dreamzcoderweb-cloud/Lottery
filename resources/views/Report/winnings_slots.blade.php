@extends('layouts.master')
@section('title', 'Winnings Slots Report - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (session('success') || session('danger'))
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show mb-5"
                role="alert">
                <strong>{{ session('success') ? session('success') : session('danger') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-3">
                <h5 class="card-header mb-0 p-0">Winnings Slots Report</h5>

                <form method="GET" action="{{ route('admin.reports.winningsslots') }}" class="d-flex flex-wrap gap-2">
                    <select name="title" class="form-select">
                        <option value="">All Ticket Types</option>
                        <option value="1" {{ request('title') == '1' ? 'selected' : '' }}>Single Digit</option>
                        <option value="2" {{ request('title') == '2' ? 'selected' : '' }}>Double Digit</option>
                        <option value="3" {{ request('title') == '3' ? 'selected' : '' }}>Three Digit</option>
                        <option value="4" {{ request('title') == '4' ? 'selected' : '' }}>Four Digit</option>
                        {{-- <option value="5" {{ request('title') == '5' ? 'selected' : '' }}>Five Digit</option> --}}
                    </select>

                    <input
                        type="date"
                        name="date"
                        class="form-control"
                        value="{{ request('date') }}"
                        placeholder="Select draw date"
                    />

                    {{-- <select name="limit" class="form-select">
                        @foreach ([10, 30, 50, 100] as $limit)
                            <option value="{{ $limit }}" {{ (int) request('limit', 30) === $limit ? 'selected' : '' }}>
                                Last {{ $limit }}
                            </option>
                        @endforeach
                    </select> --}}

                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reports.winningsslots') }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>

            <div class="table-responsive p-3">
                <table id="winnings-slots-table" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Main Title</th>
                            <th>Slot Title</th>
                            <th>Draw Date</th>
                            <th>Draw Time</th>
                            <th>Booking Close Time</th>
                            <th>Winning Groups</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $slot)
                            @php
                                $winningGroups = collect($slot['winning_groups'] ?? []);
                                $titleLabels = [
                                    '1' => 'Single Digit',
                                    '2' => 'Double Digit',
                                    '3' => 'Three Digit',
                                    '4' => 'Four Digit',
                                    '5' => 'Five Digit',
                                ];
                                $titleText = collect(explode(',', (string) ($slot['title'] ?? '')))
                                    ->map(fn ($title) => trim($title))
                                    ->filter()
                                    ->map(fn ($title) => $titleLabels[$title] ?? $title)
                                    ->implode(', ');
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $slot['main_title'] ?? '-' }}</td>
                                <td>{{ $titleText ?: ($slot['short_title'] ?? '-') }}</td>
                                <td>
                                    {{ !empty($slot['draw_date']) ? Carbon\Carbon::parse($slot['draw_date'])->format('d-m-Y') : '-' }}
                                </td>
                                <td>{{ $slot['draw_time'] ?? '-' }}</td>
                                <td>
                                    {{ !empty($slot['booking_close_time']) ? Carbon\Carbon::parse($slot['booking_close_time'])->format('h:i A') : '-' }}
                                </td>
                                <td>
                                    @if ($winningGroups->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($winningGroups as $group)
                                                <span class="badge bg-label-primary border">
                                                    Group name :
                                                    {{ $group['group_name'] ?? '-' }} -
                                                    {{ $group['digit'] ?? '-' }}
                                                    @if (!empty($group['win_amount']))
                                                        | Win: &#8377;{{ number_format((float) $group['win_amount'], 2) }}
                                                    @endif
                                                    @if (!empty($group['ticket_amt']))
                                                        | Ticket: &#8377;{{ number_format((float) $group['ticket_amt'], 2) }}
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                    <div class="booking-summary-template d-none">
                                        <div class="table-light p-3">
                                            <div class="row align-items-center g-3">
                                                <div class="col-md-2"><strong>Booking Summary</strong></div>
                                                <div class="col-md-1">Slot : {{ $slot['main_title'] ?? '-' }}</div>
                                                <div class="col-md-2">
                                                        @if ($winningGroups->isNotEmpty())
                                                            @foreach ($winningGroups as $group)
                                                                @if (!empty($group['title']))
                                                                    @php
                                                                        $titleText = $titleLabels[$group['title']] ?? $group['title'];
                                                                    @endphp
                                                                @else
                                                                    @php
                                                                        $titleText = '-';
                                                                    @endphp
                                                                @endif

                                                                <div>Title: {{ $titleText }}</div>
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                </div>
                                                <div class="col-md-1">
                                                    @if ($winningGroups->isNotEmpty())
                                                        @foreach ($winningGroups as $group)
                                                            <div>Qty: {{ $group['booking_qty'] ?? 0 }}</div>
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                                <div class="col-md-2">
                                                    @if ($winningGroups->isNotEmpty())
                                                        @foreach ($winningGroups as $group)
                                                            <div>Amount: &#8377;{{ number_format((float) ($group['booking_amount'] ?? 0), 2) }}</div>
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                                <div class="col-md-2">
                                                    @if ($winningGroups->isNotEmpty())
                                                        @foreach ($winningGroups as $group)
                                                            <div>{{ $group['group_name'] ?? '-' }} - {{ $group['digit'] ?? '-' }}</div>
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.reports.slot-details', ['slot_id' => $slot['slot_id']]) }}"
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="bx bx-show"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

