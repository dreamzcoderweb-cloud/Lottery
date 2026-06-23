@extends('layouts.master')
@section('title', 'Customers - Super Admin')
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
                <h5 class="card-header mb-0">Customer </h5>
                <div class="ms-auto">
                    <button class="btn btn-primary" >
                        + Add Customer
                    </button>
                </div>
            </div>
            <div class="table-responsive text-nowrap p-3">
                <table id="customers-table" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Mobile</th>
                            <th>Reference Code</th>
                            <th>Reference by</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->reference_code }}</td>
                               <td>{{ $customer->referredBy->name ?? '-' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">
                                                @can('customers.show')
                                                    <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.customers.show', $customer->customer_id) }}">
                                                        <i class="bx bx-show me-2"></i> View
                                                    </a>
                                                </li>
                                                @endcan


                                                <li>
                                                    <a class="dropdown-item"
                                                        href="#">
                                                        <i class="bx bx-edit-alt me-2"></i> Edit
                                                    </a>
                                                </li>



                                                <li>
                                                    <a href="#"
                                                        class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-id="#"
                                                        data-name="delete_banner">
                                                        <i class="bx bx-trash me-2"></i> Delete
                                                    </a>
                                                </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Bootstrap Table with Header - Light -->
    </div>
@endsection
