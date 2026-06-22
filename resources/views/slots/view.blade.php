@extends('layouts.master')
@section('title', 'Slots - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (session('success') || session('danger'))
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show mb-5"
                role="alert">
                <strong>{{ session('success') ? session('success') : session('danger') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Bootstrap Table with Header - Light -->
        <div class="card">
            <div class="d-flex justify-content-between align-items-center p-3">
                <h5 class="card-header mb-0">Slots </h5>
                <div class="ms-auto">
                    @can('slots.create')
                        <button class="btn btn-primary" onclick="location.href='{{ route('admin.slots.add') }}'">
                        + Add Slots
                    </button>
                    @endcan

                </div>
            </div>
            <div class="table-responsive text-nowrap p-3">
                <table id="slots-table" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Main Title</th>
                            <th>Draw Date</th>
                            <th>Draw Time</th>
                            <th>Booking Close Time</th>
                            <th>Slot Title</th>
                            @canany(['slots.edit','slots.delete'])
                            <th>Actions</th>
                            @endcanany

                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($slots as $slot)


                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $slot->main_title }}</td>
                                <td>{{ Carbon\Carbon::parse($slot->draw_date)->format('d-m-Y') }}</td>
                                <td>{{ $slot->draw_time }}</td>
                                <td>{{ $slot->booking_close_time }}</td>
                                <td>
                                    @php
                                        $titleParts = array_values(array_unique(array_filter(array_map('trim', explode(',', (string) $slot->title)), fn ($v) => $v !== '')));
                                        $labels = [
                                            '1' => 'Single Digit',
                                            '2' => 'Double Digit',
                                            '3' => 'Three Digit',
                                            '4' => 'Four Digit',
                                            '5' => 'Five Digit',
                                        ];
                                        $titleText = collect($titleParts)->map(fn ($v) => $labels[$v] ?? $v)->implode(', ');
                                    @endphp
                                    {{ $titleText }}
                                </td>

                                @canany(['slots.edit','slots.delete'])
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">

                                                {{-- Edit Permission --}}
                                                @can('slots.edit')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ url('admin/edit_slot/' . $slot->slug) }}">
                                                            <i class="bx bx-edit-alt me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                @endcan

                                                {{-- Delete Permission --}}
                                                @can('slots.delete')
                                                    <li>
                                                        <a href="#"
                                                            class="dropdown-item text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            data-id="{{ $slot->slug }}"
                                                            data-name="admin/delete_slot">
                                                            <i class="bx bx-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                @endcan

                                            </ul>
                                        </div>
                                    </td>
                                @endcanany

                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Bootstrap Table with Header - Light -->
    </div>
@endsection
