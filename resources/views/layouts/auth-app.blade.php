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
        /* Custom styles to replace Tailwind layout helpers */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc; /* light gray background */
        }
        .auth-container {
            /* Mimics min-h-screen flex flex-col items-center justify-center */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px; /* p-4 equivalent */
        }
        .auth-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); /* shadow-md equivalent */
            width: 100%;
            max-width: 440px; /* max-w-md equivalent */
        }
        
        /* Custom styles from original */
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .form-control:focus {
             /* Custom focus to match original Tailwind/custom style */
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card card rounded-3 overflow-hidden">
            <div class="text-center py-4">
                <img src="{{ asset('images/arbifA.png') }}" alt="KGSons Logo" class="h-20" style="height: 80px;">
            </div>
            
            <div class="card-header bg-light border-bottom text-center">
                <h2 class="h5 font-weight-bold text-gray-800 mb-0">
                    @yield('auth-title')
                </h2>
            </div>
            
            <div class="card-body">
                @yield('content')
            </div>
            
            <div class="card-footer bg-light text-center text-muted border-top">
                <small>@yield('auth-footer')</small>
            </div>
            
            <div class="p-3 text-center text-sm text-muted">
                @yield('auth-links')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality - Using Bootstrap classes
            const passwordFields = document.querySelectorAll('input[type="password"]');
            
            passwordFields.forEach(field => {
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative';
                field.parentNode.insertBefore(wrapper, field);
                wrapper.appendChild(field);
                
                const toggleIcon = document.createElement('i');
                // Ensure you have Bootstrap Icons loaded for bi-eye/bi-eye-slash
                toggleIcon.className = 'bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3';
                toggleIcon.style.cursor = 'pointer';
                toggleIcon.style.color = '#6b7280';
                
                toggleIcon.addEventListener('click', function() {
                    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                    field.setAttribute('type', type);
                    this.className = type === 'password' 
                        ? 'bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3' 
                        : 'bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3';
                });
                
                wrapper.appendChild(toggleIcon);
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (!alert.classList.contains('alert-permanent')) {
                    setTimeout(() => {
                        // Use Bootstrap's built-in functionality for a smooth fade-out if possible, 
                        // or stick to the custom JS transition for simplicity
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