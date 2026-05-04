<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - ArBif Management System</title>
    <link rel="icon" href="{{ asset('images/arbifA.png') }}" type="image/png">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        /* ─── Full-screen auth layout (used by login, register, etc.) ─── */
        .auth-fullscreen {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: stretch;
        }

        /* ─── Centered card layout (used by simple pages like forgot-password) ─── */
        .auth-container {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-card {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 440px;
            border-radius: 12px;
            overflow: hidden;
        }

        .btn-primary {
            background-color: #0C447C;
            border-color: #0C447C;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #185FA5;
            border-color: #185FA5;
        }
        .form-control:focus {
            border-color: #378ADD;
            box-shadow: 0 0 0 3px rgba(55,138,221,0.15);
        }
    </style>
</head>
<body>
    @yield('content')

    @include('sweetalert::alert')
    <script src="{{ asset('js/sweetalert-custom.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/sweetalert-ajax.js') }}"></script>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (!alert.classList.contains('alert-permanent')) {
                    setTimeout(() => {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }, 5000);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>