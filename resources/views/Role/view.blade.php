@extends('layouts.master')
@section('title', 'Roles - Super Admin')
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
                <h5 class="card-header mb-0">Roles </h5>
                <div class="ms-auto">
                    <button class="btn btn-primary" onclick="location.href='{{ url('admin/add_role') }}'">
                        + Add Role
                    </button>
                </div>
            </div>
            <div class="table-responsive text-nowrap p-3">
                <table id="roles-table" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->permissions_count }}</td>
                                <td>{{ $role->created_at?->format('d-m-Y') }}</td>
                                {{-- <td>
                                    <div class="dropdown">
                                        <a class="btn btn-outline-primary btn-edit"
                                            href="{{ url('admin/edit_role/' . $role->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-danger btn-delete" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $role->id }}"
                                            data-name="admin/delete_role">
                                            <i class="bx bx-trash me-1"></i>
                                        </a>
                                    </div>
                                </td> --}}
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            {{-- Edit Permission --}}
                                            @can('roles.edit')
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/edit_role/' . $role->id) }}">
                                                        <i class="bx bx-edit-alt me-2"></i> Edit
                                                    </a>
                                                </li>
                                            @endcan

                                            {{-- Delete Permission --}}
                                            @can('roles.delete')
                                                <li>
                                                    <a href="#"
                                                        class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-id="{{ $role->id }}"
                                                        data-name="admin/delete_role">
                                                        <i class="bx bx-trash me-2"></i> Delete
                                                    </a>
                                                </li>
                                            @endcan

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
