
<!-- ================================================================ -->
<!-- FILE: resources/views/layouts/partials/header.blade.php -->
<!-- ================================================================ -->
<header class="main-header sticky-top">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center py-2">
            <!-- Toggle Sidebar Button -->
            <button class="btn btn-outline-secondary border-0" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            
            <!-- Page Title -->
            <h4 class="mb-0 text-primary">@yield('page-title', 'Dashboard')</h4>
            
            <!-- Header Actions -->
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                        <li class="dropdown-header">
                            <strong>Arifa</strong>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        

                            <li>
                                <span class="dropdown-item text-muted">Hakuna arifa mpya</span>
                            </li>

                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-center text-primary" href="">
                                Tazama zote
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle" 
                            type="button" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-2">
                                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->username }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <div class="text-center">
                                <strong>{{ Auth::user()->username }}</strong><br>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bi bi-person me-2"></i>Wasifu Wangu
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bi bi-gear me-2"></i>Mipangilio
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <a class="dropdown-item text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Toka
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

