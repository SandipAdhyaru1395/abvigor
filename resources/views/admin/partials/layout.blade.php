<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') ? config('app.name') : '' }}</title>
    @include('admin.partials.styles')
    <style>
        body {
            height: 100%;
            margin: 0;
            font-size: 0.875rem !important;
        }

        .bg-base {
            background-color: #ed1c24;
        }

        .bg-base:hover {
            background-color: rgb(223, 116, 120);
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.8);
            display: none;
            /* Flexbox is essential */
            align-items: center;
            justify-content: center;
            z-index: 9999;
            pointer-events: all;
        }

        .text-base {
            color: #ed1c24;
        }

        .page-item.active .page-link {
            background-color: #ed1c24;
        }

        .form-control {
            border-color: #adb5bd;
        }

        .form-select {
            border-color: #adb5bd;
        }

        .form-control:focus {
            border-color: #ed1c24;
            box-shadow: 0 0 0 .25rem rgb(253 13 13 / 14%);
        }

        .error-text {
            color: red;
            font-size: 14px;
        }

        select {
            font-size: 0.875rem !important;
        }

        /* Global Select2 Styling to match form controls height */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: auto !important;
            min-height: 38px;
            border: 1px solid #adb5bd;
            border-radius: 8px;
            padding: 0;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            background-color: #fff;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding: 8px 12px;
            color: #212529;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Select2 Clear Button Styling */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            cursor: pointer;
            font-weight: bold;
            position: absolute;
            right: 28px;
            top: 50%;
            transform: translateY(-50%);
            height: 24px;
            width: 24px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 22px;
            margin: 0;
            padding: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #333;
        }

        /* Adjust arrow position when clear button is present */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-right: 50px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #adb5bd;
        }

        /* Select2 Dropdown Styling */
        .select2-dropdown {
            border: 1px solid #adb5bd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #adb5bd;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.9rem;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.15);
        }

        /* Prevent Bootstrap button hover color from being applied globally */
        .btn:hover,
        .btn:focus {
            color: #ffffff !important;
        }

        .admin.sidebar {
            position: sticky;
            top: 0;
            width: 50%;
            height: 100vh;
            max-width: 280px;
        }

        .admin.container {
            display: flex;
        }

        .admin.main-content {
            width: 80%;
            margin-left: 50px;
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        /* Large screens - sidebar hidden, navbar menu shown */
        @media (min-width: 993px) {
            .admin.sidebar {
                display: none !important;
            }

            .admin.container {
                display: block;
            }

            .admin.main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Small screens - sidebar shown, navbar menu hidden */
        @media (max-width: 992px) {
            .admin.sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                z-index: 1040;
                transition: left 0.3s ease-in-out;
                max-width: 280px;
            }

            .admin.sidebar.show {
                left: 0;
            }

            .admin.overlay {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1039;
            }

            .admin.overlay.hide {
                display: none;
            }

            .admin.container {
                display: block;
                min-width: 100%;
                margin: 0;
            }

            .admin.main-content {
                margin-left: 0px;
                width: 100%;
            }
        }

        /* Darker Toastr Notifications */
        #toast-container > div {
            opacity: 0.95 !important;
            /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important; */
        }

        /* #toast-container > .toast-success {
            background-color: #1e7e34 !important;
            color: #ffffff !important;
        }

        #toast-container > .toast-error {
            background-color: #721c24 !important;
            color: #ffffff !important;
        }

        #toast-container > .toast-info {
            background-color: #004085 !important;
            color: #ffffff !important;
        }

        #toast-container > .toast-warning {
            background-color: #856404 !important;
            color: #ffffff !important;
        }

        #toast-container .toast-message {
            color: #ffffff !important;
            font-weight: 500;
        }

        #toast-container .toast-title {
            color: #ffffff !important;
            font-weight: 600;
        } */
    </style>
</head>

<body>
    <div id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @yield('content')
    @include('admin.partials.scripts')
    {!! Toastr::message() !!}

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const overlay = document.getElementById('overlay');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('hide');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.add('hide');
        });

        function showLoader() {
            document.getElementById('loader').style.display = 'flex';
        }

        function hideLoader() {
            document.getElementById('loader').style.display = 'none';
        }

        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('.select2').select2({
                // theme: 'bootstrap-5',
                placeholder: $(this).data('placeholder') || "Select an option",
                allowClear: true,
                width: '100%',
            });
        });
    </script>
</body>

</html>