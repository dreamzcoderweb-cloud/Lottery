@extends('layouts.master')
@section('title', 'Dashboard - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <div class="text-muted small">Quick overview of your admin panel</div>
            </div>
            <div class="text-muted small">
                <i class="bx bx-calendar me-1"></i> {{ now()->format('d M Y') }}
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-4">
                @can('customers.view')
                    <a href="{{ route('admin.customers.index') }}" class="text-body text-decoration-none d-block h-100">
                @endcan
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-primary p-3 rounded">
                                <i class="bx bx-user bx-sm"></i>
                            </span>
                            <div>
                                <div class="text-muted small">Customers</div>
                                <div class="h3 mb-0">{{ number_format($totalcustomers ?? 0) }}</div>
                                <div class="text-muted small">Total registered</div>
                            </div>
                        </div>
                        @can('customers.view')
                            <i class="bx bx-chevron-right text-muted"></i>
                        @endcan
                    </div>
                </div>
                @can('customers.view')
                    </a>
                @endcan
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                @can('staff.view')
                    <a href="{{ route('admin.staff.index') }}" class="text-body text-decoration-none d-block h-100">
                @endcan
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-warning p-3 rounded">
                                <i class="bx bx-group bx-sm"></i>
                            </span>
                            <div>
                                <div class="text-muted small">Staff</div>
                                <div class="h3 mb-0">{{ number_format($totalstaff ?? 0) }}</div>
                                <div class="text-muted small">Excluding Admin</div>
                            </div>
                        </div>
                        @can('staff.view')
                            <i class="bx bx-chevron-right text-muted"></i>
                        @endcan
                    </div>
                </div>
                @can('staff.view')
                    </a>
                @endcan
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                @can('roles.view')
                    <a href="{{ route('admin.roles.index') }}" class="text-body text-decoration-none d-block h-100">
                @endcan
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-success p-3 rounded">
                                <i class="bx bx-shield-quarter bx-sm"></i>
                            </span>
                            <div>
                                <div class="text-muted small">Roles</div>
                                <div class="h3 mb-0">{{ number_format($totalroles ?? 0) }}</div>
                                <div class="text-muted small">Access control</div>
                            </div>
                        </div>
                        @can('roles.view')
                            <i class="bx bx-chevron-right text-muted"></i>
                        @endcan
                    </div>
                </div>
                @can('roles.view')
                    </a>
                @endcan
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                @can('slots.view')
                    <a href="{{ route('admin.slots.index') }}" class="text-body text-decoration-none d-block h-100">
                @endcan
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-success p-3 rounded">
                                <i class="bx bx-shield-quarter bx-sm"></i>
                            </span>
                            <div>
                                <div class="text-muted small">Solts</div>
                                <div class="h3 mb-0">{{ number_format($totalslots ?? 0) }}</div>
                                <div class="text-muted small">Total Slots</div>
                            </div>
                        </div>
                        @can('slots.view')
                            <i class="bx bx-chevron-right text-muted"></i>
                        @endcan
                    </div>
                </div>
                @can('slots.view')
                    </a>
                @endcan
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                @can('slots.view')
                    <a href="{{ route('admin.slots.index') }}" class="text-body text-decoration-none d-block h-100">
                @endcan
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-success p-3 rounded">
                                <i class="bx bx-shield-quarter bx-sm"></i>
                            </span>
                            <div>
                                <div class="text-muted small">Today's Winnings</div>
                                <div class="h3 mb-0">{{ number_format($todaywinnings_slot) }}</div>
                                <div class="text-muted small">Total Winnings</div>
                            </div>
                        </div>
                        @can('slots.view')
                            <i class="bx bx-chevron-right text-muted"></i>
                        @endcan
                    </div>
                </div>
                @can('slots.view')
                    </a>
                @endcan
            </div>

        </div>

        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <div class="fw-semibold">Quick actions</div>
                        <div class="text-muted small">Jump to common admin tasks</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @can('customers.view')
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-user me-1"></i> Customers
                            </a>
                        @endcan
                        @can('staff.view')
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-warning btn-sm">
                                <i class="bx bx-group me-1"></i> Staff
                            </a>
                        @endcan
                        @can('roles.view')
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="bx bx-shield-quarter me-1"></i> Roles
                            </a>
                        @endcan
                        @can('banners.view')
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-image me-1"></i> Banners
                            </a>
                        @endcan
                        @can('slots.view')
                            <a href="{{ route('admin.slots.index') }}" class="btn btn-outline-secondary btn-sm">
                                 <i class="menu-icon tf-icons bx bx-purchase-tag-alt"></i> Slots
                            </a>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success') || session('danger'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="dashboardToast" class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') ?: session('danger') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var dashboardToast = new bootstrap.Toast(document.getElementById('dashboardToast'));
                dashboardToast.show();
            });
        </script>
    @endif
@endsection
