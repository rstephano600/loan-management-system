<header class="main-header sticky-top">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center py-2">
            <button class="btn btn-primary d-flex align-items-center shadow-sm" id="sidebarToggle" title="Toggle Sidebar">
                <i class="bi bi-list fs-5 text-white"></i>
            </button>
            
            <h4 class="mb-0 text-primary d-none d-sm-block">@yield('page-title', 'Dashboard')</h4>
            
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-link text-dark position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5 text-secondary"></i>
                        {{-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            3
                            <span class="visually-hidden">unread messages</span>
                        </span> --}}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 300px;">
                        <li class="dropdown-header">
                            <strong>Notfications</strong>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <span class="dropdown-item text-muted">No new informations</span>
                        </li>

                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-center text-primary" href="">
                                View all
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle p-0" 
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-2 d-none d-sm-flex">
                                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                            </div>
                            <span class="d-none d-md-inline text-dark">{{ Auth::user()->username }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li class="dropdown-header text-truncate">
                            <div class="text-center">
                                <strong>{{ Auth::user()->username }}</strong><br>
                                <small class="text-muted text-truncate">{{ Auth::user()->email }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2 text-primary"></i>My profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bi bi-gear me-2 text-primary"></i>Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <a class="dropdown-item text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>