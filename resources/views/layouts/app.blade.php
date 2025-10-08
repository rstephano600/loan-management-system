<!-- ================================================================ -->
<!-- FILE: resources/views/layouts/app.blade.php -->
<!-- ================================================================ -->
<!DOCTYPE html>
<html lang="sw" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ArBif DBMS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #2980b9;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Header Styles */
        .main-header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 999;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
            }
            
            .sidebar.mobile-expanded {
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }
            
            .main-content.mobile-expanded {
                margin-left: var(--sidebar-width);
            }
            
            .sidebar .nav-link span {
                display: none;
            }
            
            .sidebar.mobile-expanded .nav-link span {
                display: inline;
            }
        }
        
        /* Role Badges */
        .role-badge {
            font-size: 0.7em;
            padding: 3px 8px;
        }
        
        .badge-admin { background-color: #e74c3c; }
        .badge-director { background-color: #8e44ad; }
        .badge-ceo { background-color: #c0392b; }
        .badge-shareholders { background-color: #16a085; }
        .badge-manager { background-color: #2980b9; }
        .badge-marketing-officer { background-color: #e67e22; }
        .badge-hr { background-color: #27ae60; }
        .badge-accountant { background-color: #f39c12; }
        .badge-secretary { background-color: #95a5a6; }
        .badge-loan-officer { background-color: #3498db; }
        .badge-client { background-color: #d35400; }
        .badge-user { background-color: #7f8c8d; }
    </style>

    <style>
         #flash-message-container {
           position: fixed;
           top: 20px;
           right: 20px;
           z-index: 1050; 
           max-width: 350px; 
           transition: opacity 0.5s ease-in-out;
        }
        #flash-message-container .alert {
            margin-bottom: 10px; 
        }
</style>
    
    @stack('styles')
</head>
<body>
    <!-- Include Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Include Header -->
        @include('layouts.partials.header')

        <!-- Main Content Area -->
        <main class="flex-grow-1 p-3">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif

                 <div id="flash-message-container">
                     @if(session('success'))
                         <div class="alert alert-success alert-dismissible fade show" role="alert">
                             <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                         </div>
                     @endif
    
                     @if(session('error'))
                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                             <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                         </div>
                     @endif
                 </div>
                <!-- Page Content -->
                @yield('content')
            </div>
        </main>

        <!-- Include Footer -->
        @include('layouts.partials.footer')
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>

    
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const logoText = document.getElementById('logo-text');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-expanded');
                mainContent.classList.toggle('mobile-expanded');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                if (logoText) {
                    logoText.style.display = sidebar.classList.contains('collapsed') ? 'none' : 'inline';
                }
            }
        });

        // Auto-collapse sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target) &&
                sidebar.classList.contains('mobile-expanded')) {
                sidebar.classList.remove('mobile-expanded');
                document.getElementById('mainContent').classList.remove('mobile-expanded');
            }
        });

        // Update active nav link based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && href !== '' && currentPath.startsWith(href)) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {

    const alerts = document.querySelectorAll('#flash-message-container .alert');

    alerts.forEach(alertElement => {
        setTimeout(() => {
            if (alertElement) {
                const closeButton = alertElement.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                } else {
                    var bsAlert = new bootstrap.Alert(alertElement);
                    bsAlert.close();
                }
            }
        }, 5000);
    });
});
</script>
    
    @stack('scripts')
</body>
</html>
