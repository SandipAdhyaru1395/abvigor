<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @include('front.partials.styles')
    <style>
        body {
            height: 100%;
            margin: 0;
            font-size: 0.875rem !important;
        }

        .bg-base {
            background-color: #ed1c24;
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

        /* Prevent Bootstrap button hover color from being applied globally */
        .btn:hover,
        .btn:focus {
            color: #ffffff !important;
        }

        /* Hidden Checkbox */
        .sidebar-toggle-checkbox {
            display: none;
        }

        /* Toggle Button - Positioned outside sidebar */
        .front-sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 20px;
            z-index: 1051;
            background: #ed1c24;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(237, 28, 36, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #ffffff;
            font-size: 18px;
            padding: 0;
        }

        .front-sidebar-toggle:hover {
            background: #c91a20;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(237, 28, 36, 0.5);
        }

        /* Icon switching - show bars by default, hide close */
        .front-sidebar-toggle .toggle-icon-close {
            display: none;
        }

        .front-sidebar-toggle .toggle-icon-bars {
            display: inline-block;
        }

        /* When sidebar is open (checkbox checked), show X icon, hide bars */
        #sidebar-toggle:checked ~ .front-sidebar-toggle .toggle-icon-bars {
            display: none;
        }

        #sidebar-toggle:checked ~ .front-sidebar-toggle .toggle-icon-close {
            display: inline-block;
        }

        /* Fixed Sidebar - Hidden by default */
        .front.sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1040;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Sidebar visible when checkbox checked */
        #sidebar-toggle:checked ~ .front.sidebar {
            transform: translateX(0);
        }

        /* Toggle button position when sidebar is open */
        #sidebar-toggle:checked ~ .front-sidebar-toggle {
            left: 290px;
        }

        .sidebar-header {
            padding: 20px;
            background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
            color: #ffffff;
            margin-bottom: 0;
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
        }

        .sidebar-header h5 i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .front.sidebar .nav {
            padding: 15px 0;
        }

        .front.sidebar .nav-link {
            padding: 12px 25px;
            color: #495057;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .front.sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
            text-align: center;
        }

        .front.sidebar .nav-link:hover {
            background: rgba(237, 28, 36, 0.1);
            color: #ed1c24;
            border-left-color: #ed1c24;
            padding-left: 30px;
        }

        .front.sidebar .nav-link.active {
            background: rgba(237, 28, 36, 0.15);
            color: #ed1c24;
            border-left-color: #ed1c24;
            font-weight: 700;
        }

        .front.container,
        .front.container-fluid {
            display: block;
        }

        .front.main-content {
            flex: 1;
            margin-left: 0;
            padding: 30px;
            min-height: calc(100vh - 200px);
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        /* Overlay - Hidden by default */
        .front.overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
            cursor: pointer;
        }

        /* Show overlay when checkbox checked (mobile) */
        #sidebar-toggle:checked ~ .front.overlay {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .front-sidebar-toggle {
                width: 40px;
                height: 40px;
                font-size: 18px;
                top: 15px;
                left: 15px;
            }

            .front.sidebar {
                width: 280px;
            }

            /* Button outside sidebar when open on mobile */
            #sidebar-toggle:checked ~ .front-sidebar-toggle {
                left: 290px;
            }

            .front.main-content {
                margin-left: 0;
                padding: 20px;
            }


            .front.container,
            .front.container-fluid {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .front.sidebar {
                width: 260px;
            }

            /* Button outside 260px sidebar on small screens */
            #sidebar-toggle:checked ~ .front-sidebar-toggle {
                left: 270px;
            }

            .sidebar-header {
                padding: 15px;
            }

            .sidebar-header h5 {
                font-size: 1.1rem;
            }

            .front.sidebar .nav-link {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .front.main-content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    @include('front.partials.header')
    <div id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @yield('content')

    @include('front.partials.footer')

    @include('front.partials.scripts')
    {!! Toastr::message() !!}

    <script>
        function showLoader() {
            document.getElementById('loader').style.display = 'flex';
        }

        function hideLoader() {
            document.getElementById('loader').style.display = 'none';
        }
    </script>
</body>

</html>
