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

        .front.sidebar {
            position: sticky;
            top: 0;
            width: 50%;
            height: 100vh;
            background-color: #f8f9fa;
            max-width: 250px;
        }

        .front.container {
            display: flex;
        }

        .front.main-content {
            width: 80%;
            margin-left: 50px;
        }

        .nav-link {
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link.active {
            background-color: #ed1c24;
            color: #e9ecef;
            /* transition: background-color 0.3s, color 0.3s; */
        }

        .nav-link:hover {
            background-color: #e9ecef;
            /* Light gray background on hover */
            color: #ed1c24;
            /* Bootstrap primary color */
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        @media (max-width: 992px) {
            .front.container {
                min-width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 768px) {
            .front.sidebar {
                position: fixed;
                top: 0;
                left: -50%;
                height: 100vh;
                z-index: 1040;
                transition: left 0.3s ease-in-out;
            }

            .front.sidebar.show {
                left: 0;
            }

            .front.overlay {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1039;
            }

            .front.overlay.hide {
                display: none;
            }

            .front.container {
                display: block;
            }

            .front.main-content {
                margin-left: 0px;
                width: 100%;
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
    </script>
</body>

</html>
