@extends('layouts.master')
@section('title', 'Admin Panel Settings - Super Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0">Admin Panel Settings</h4>
        </div> --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <!-- QR Image Upload Option -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center">
                                <i class="bx bx-qr me-2 text-primary fs-4"></i> QR Code Settings
                            </h5>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4 text-center">
                                <label class="form-label fw-semibold d-block mb-2">Current QR Code Image</label>
                                <div class="p-3 bg-light border rounded d-inline-block shadow-sm">
                                    @if (!empty($qrImage) && file_exists(public_path('assets/img/qr_code/' . $qrImage)))
                                        <a href="{{ asset('assets/img/qr_code/' . $qrImage) }}" target="_blank">
                                            <img src="{{ asset('assets/img/qr_code/' . $qrImage) }}" alt="QR Code" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                                        </a>
                                    @else
                                        <div class="text-muted py-4 px-3" style="min-width: 180px;">
                                            <i class="bx bx-image-alt fs-1 d-block mb-2 text-secondary"></i>
                                            <span>No QR Image Uploaded</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="qr_image" class="form-label fw-bold">Upload New QR Code Image</label>
                                <input class="form-control" type="file" id="qr_image" name="qr_image" accept="image/*">
                                <div class="form-text text-muted mt-1">Allowed formats: JPG, PNG, WEBP, GIF, SVG. Max size: 5MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Picker Option -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center">
                                <i class="bx bx-palette me-2 text-primary fs-4"></i> Theme & Color Settings
                            </h5>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4">
                                <label for="theme_color" class="form-label fw-bold">Admin Theme Primary Color</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="color" class="form-control form-control-color" id="theme_color_picker" value="{{ old('theme_color', $themeColor) }}" style="width: 60px; height: 42px; cursor: pointer;">
                                    <input type="text" class="form-control fw-bold" id="theme_color" name="theme_color" value="{{ old('theme_color', $themeColor) }}" placeholder="#696cff" maxlength="7" style="max-width: 150px;">
                                </div>
                                <div class="form-text text-muted mt-2">Pick a custom primary color to dynamically style the sidebar active items and buttons throughout the admin panel.</div>
                            </div>

                            <!-- Color Presets -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">Quick Presets:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #696cff;" data-color="#696cff">Default Blue</button>
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #2b9348;" data-color="#2b9348">Emerald</button>
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #0077b6;" data-color="#0077b6">Ocean</button>
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #7209b7;" data-color="#7209b7">Purple</button>
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #d90429;" data-color="#d90429">Crimson</button>
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #1b4332;" data-color="#1b4332">Dark Green</button>
                                    <button type="button" class="btn btn-sm text-white color-preset-btn" style="background-color: #d97706;" data-color="#d97706">Amber</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-body text-end py-3">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                        <i class="bx bx-save me-1"></i> Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorPicker = document.getElementById('theme_color_picker');
            const colorInput = document.getElementById('theme_color');
            const previewSidebar = document.getElementById('preview-sidebar');
            const previewButton = document.getElementById('preview-button');
            const presetBtns = document.querySelectorAll('.color-preset-btn');

            const previewSidebarActive = document.getElementById('preview-sidebar-active');

            function updateColor(color) {
                colorPicker.value = color;
                colorInput.value = color;
                if (previewSidebar) previewSidebar.style.backgroundColor = color;
                if (previewSidebarActive) previewSidebarActive.style.color = color;
                if (previewButton) {
                    previewButton.style.backgroundColor = color;
                    previewButton.style.borderColor = color;
                }
            }

            colorPicker.addEventListener('input', function() {
                updateColor(this.value);
            });

            colorInput.addEventListener('input', function() {
                if (/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/.test(this.value)) {
                    updateColor(this.value);
                }
            });

            presetBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const color = this.getAttribute('data-color');
                    updateColor(color);
                });
            });
        });
    </script>
@endsection
