@extends('layouts.master')
@section('title', 'Banners - Super Admin')
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
                <h5 class="card-header mb-0">Banners With Filter</h5>
                <div class="ms-auto">
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                        <i class='bx bx-filter-alt'></i>&nbsp; Filter
                    </button>
                    <button class="btn btn-primary" onclick="location.href='{{ url('admin/add_banner') }}'">
                        + Add Banner
                    </button>
                </div>
            </div>
            <div class="table-responsive text-nowrap p-3">
                <table id="banners-table" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            {{-- <th>Short Title</th> --}}
                            {{-- <th>Title</th> --}}
                            {{-- <th>Sequence</th> --}}
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($banners as $banner)
                            <tr>
                                <td><img src="{{ asset('assets/img/banner/' . $banner->image) }}" alt="banner image" class="rounded" width="50"
                                        height="50"></td>
                                {{-- <td>{{ $banner->short_title }}</td> --}}
                                {{-- <td>{{ $banner->title }}</td> --}}
                                {{-- <td>{{ $banner->sequence }}</td> --}}
                                <td>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input change_banner_status my-element" type="checkbox"
                                            id="flexSwitchCheckChecked" data-id="{{ $banner->id }}"
                                            {{ $banner->status == 'Active' ? 'checked' : '' }}>
                                    </div>
                                    <span id="status_msg_{{ $banner->id }}" style="display: none;"></span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            {{-- Edit Permission --}}
                                            @can('banners.edit')
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/edit_banner/' . $banner->id) }}">
                                                        <i class="bx bx-edit-alt me-2"></i> Edit
                                                    </a>
                                                </li>
                                            @endcan

                                            {{-- Delete Permission --}}
                                            @can('banners.delete')
                                                <li>
                                                    <a href="#"
                                                        class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-id="{{ $banner->id }}"
                                                        data-name="admin/delete_banner">
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

    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-6">
                            <label for="nameBasic" class="form-label">Banner Status</label>
                            <select class="form-select" id="status_filter" aria-label="Default select example"
                                name="status">
                                <option value='' selected>Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="status_filter_button">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.canEditBanners = @json(auth()->user()->can('banners.edit'));
        window.canDeleteBanners = @json(auth()->user()->can('banners.delete'));
    </script>
@endsection
