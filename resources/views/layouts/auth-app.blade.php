<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Loan Management System</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/arbifA.png') }}" type="image/png">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">

     <!-- Select2 CSS -->
     <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

     <!-- Your custom CSS if needed -->
     <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        .auth-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #3b82f6;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md mx-auto">
            <!-- Company Logo -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/arbifA.png') }}" alt="KGSons Logo" class="h-20">
            </div>
            
            <!-- Card Container -->
            <div class="bg-white rounded-lg shadow-md auth-card overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800 text-center">
                        @yield('auth-title')
                    </h2>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    @yield('content')
                </div>
                
                <!-- Card Footer -->
                <div class="px-6 py-4 bg-gray-50 text-center text-sm text-gray-600">
                    @yield('auth-footer')
                </div>
            </div>
            
            <!-- Additional Links -->
            <div class="mt-6 text-center text-sm text-gray-600">
                @yield('auth-links')
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordFields = document.querySelectorAll('input[type="password"]');
            
            passwordFields.forEach(field => {
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative';
                field.parentNode.insertBefore(wrapper, field);
                wrapper.appendChild(field);
                
                const toggleIcon = document.createElement('i');
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
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 500);
                    }, 5000);
                }
            });
            
            // Add focus styles to form inputs
            const formInputs = document.querySelectorAll('.form-control');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>