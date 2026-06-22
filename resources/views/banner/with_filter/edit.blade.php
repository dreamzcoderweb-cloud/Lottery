@extends('layouts.master')
@section('title', 'Banners - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y mx-auto" style="max-width: 75%;">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Edit Banner</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/edit_banner/' . request()->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Short Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="short_title" name="short_title"
                                        value="{{ $banner->short_title }}" />
                                    <span class="text-danger">
                                        {{ $errors->first('short_title') }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-company">Title <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $banner->title }}" />
                                    <span class="text-danger">
                                        {{ $errors->first('title') }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-company">Image <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input class="form-control" type="file" id="image" name="image" />
                                    <div class="pt-1"><strong>Recommended Size: 493 x 640 px</strong> | Allowed Types:
                                        jpg,jpeg,png | Maxfile Size: 1MB</div>
                                    <div class="text-danger">
                                        {{ $errors->first('image') }}
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <img src="{{ asset('assets/img/banner/' . $banner->image) }}" class="rounded"
                                        alt="banner image" width="100" height="100">
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-phone">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control ckeditor" id="description" name="description" rows="2">{{ $banner->description }}</textarea>
                                    <span class="text-danger">
                                        {{ $errors->first('description') }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-message">Sequence <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="sequence" name="sequence"
                                        value="{{ $banner->sequence }}" />
                                    <span class="text-danger">
                                        {{ $errors->first('sequence') }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-message">Link</label>
                                <div class="col-sm-10">
                                    <input type="url" class="form-control" id="link" name="link"
                                        value="{{ $banner->link }}" />
                                    <span class="text-danger">
                                        {{ $errors->first('url') }}
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-message">Status <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" aria-label="Default select example"
                                        name="status">
                                        <option value='' selected>Select Status</option>
                                        <option value="Active" {{ $banner->status == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ $banner->status == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    <span class="text-danger">
                                        {{ $errors->first('status') }}
                                    </span>
                                </div>
                            </div> --}}
                            <div class="row justify-content-end">
                                <div class="col-sm-10 text-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('admin/banners') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
