<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', 'Super Admin')</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/fav_icon.png') }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- datatable css cdn -->
    <script defer src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script defer src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap5.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>
    <!--datatable css cdn-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.bootstrap5.css">

    <!-- ckeditor -->
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script>
        $('.ckeditor').ckeditor();
    </script>

    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>

    @php
        $adminThemeColor = \App\Models\Setting::get('theme_color', '#696cff');
        $hex = ltrim($adminThemeColor, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $adminThemeRgb = "$r, $g, $b";

        $rDark = max(0, (int)($r * 0.85));
        $gDark = max(0, (int)($g * 0.85));
        $bDark = max(0, (int)($b * 0.85));
        $adminThemeDarker = sprintf("#%02x%02x%02x", $rDark, $gDark, $bDark);
    @endphp

    <style>
        :root {
            --bs-primary: {{ $adminThemeColor }};
            --bs-primary-rgb: {{ $adminThemeRgb }};
        }

        /* Full Sidebar Theme Background */
        #layout-menu,
        #layout-menu.bg-menu-theme,
        aside#layout-menu {
            background-color: {{ $adminThemeColor }} !important;
            color: #ffffff !important;
        }

        /* App Brand & Logo in Sidebar */
        #layout-menu .app-brand {
            background-color: transparent !important;
        }
        #layout-menu .app-brand-text,
        #layout-menu .app-brand a {
            color: #ffffff !important;
        }

        /* Sidebar Menu Items, Icons & Links */
        #layout-menu .menu-link,
        #layout-menu .menu-icon,
        #layout-menu .menu-header-text,
        #layout-menu .menu-item a,
        #layout-menu .menu-item div,
        #layout-menu .layout-menu-toggle i {
            color: rgba(255, 255, 255, 0.88) !important;
        }

        /* Hover states */
        #layout-menu .menu-inner > .menu-item > .menu-link:hover,
        #layout-menu .menu-inner > .menu-item > .menu-link:focus {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        /* Sub-menu items & hover */
        #layout-menu .menu-sub > .menu-item > .menu-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        #layout-menu .menu-sub > .menu-item > .menu-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
        }
        #layout-menu .menu-sub > .menu-item > .menu-link:before {
            background-color: rgba(255, 255, 255, 0.7) !important;
        }

        /* Active Menu Item (white pill with theme color text for crisp contrast) */
        #layout-menu .menu-inner > .menu-item.active > .menu-link {
            background-color: #ffffff !important;
            color: {{ $adminThemeColor }} !important;
            box-shadow: 0 0.125rem 0.375rem 0 rgba(0, 0, 0, 0.2) !important;
            font-weight: 700 !important;
        }
        #layout-menu .menu-inner > .menu-item.active > .menu-link .menu-icon,
        #layout-menu .menu-inner > .menu-item.active > .menu-link div {
            color: {{ $adminThemeColor }} !important;
        }

        /* Active Sub-menu Item */
        #layout-menu .menu-sub > .menu-item.active > .menu-link {
            color: #ffffff !important;
            font-weight: 700 !important;
            background-color: rgba(255, 255, 255, 0.2) !important;
        }
        #layout-menu .menu-sub > .menu-item.active > .menu-link:before {
            background-color: #ffffff !important;
        }

        /* Open Dropdown Parent */
        #layout-menu .menu-item.open > .menu-link {
            background-color: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
        }

        /* Hide menu inner shadow overlay on dark background */
        #layout-menu .menu-inner-shadow {
            display: none !important;
        }

        /* Primary & Success Action Buttons */
        .btn-primary,
        .btn-success {
            background-color: {{ $adminThemeColor }} !important;
            border-color: {{ $adminThemeColor }} !important;
            color: #ffffff !important;
            box-shadow: 0 0.125rem 0.25rem 0 rgba({{ $adminThemeRgb }}, 0.4) !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active,
        .btn-success:hover, .btn-success:focus, .btn-success:active, .btn-success.active {
            background-color: {{ $adminThemeDarker }} !important;
            border-color: {{ $adminThemeDarker }} !important;
        }

        /* Outline Buttons */
        .btn-outline-primary {
            color: {{ $adminThemeColor }} !important;
            border-color: {{ $adminThemeColor }} !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: {{ $adminThemeColor }} !important;
            border-color: {{ $adminThemeColor }} !important;
            color: #ffffff !important;
        }

        /* Badges, text & UI elements */
        .bg-primary {
            background-color: {{ $adminThemeColor }} !important;
        }
        .text-primary {
            color: {{ $adminThemeColor }} !important;
        }
        .page-item.active .page-link, .page-item.active .page-link:hover, .page-item.active .page-link:focus {
            background-color: {{ $adminThemeColor }} !important;
            border-color: {{ $adminThemeColor }} !important;
        }
        .form-check-input:checked {
            background-color: {{ $adminThemeColor }} !important;
            border-color: {{ $adminThemeColor }} !important;
        }
        .nav-pills .nav-link.active, .nav-pills .nav-link.active:hover, .nav-pills .nav-link.active:focus {
            background-color: {{ $adminThemeColor }} !important;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.header')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        Close
                    </button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- custom JS -->
    <script src="{{ asset('assets/js/custom-js/banner.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/staff.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/roles.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/customer.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/service.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/blog.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/contact_enquiries.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/newsletter_subscription.js') }}"></script>
    <script src="{{ asset('assets/js/custom-js/slot.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
