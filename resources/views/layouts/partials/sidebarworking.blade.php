<aside class="sidebar" id="sidebar">
    @php
        // Get the authenticated user
        $user = Auth::user();
        
        // Define simple permission variables using the new methods
        $canManageAll = $user->isAdmin();
        $isLoanOfficer = $user->isLoanOfficer();
        $canManageHisData = $user->isLoanOfficer();
        $canManageLoans = $user->isAdmin() || $user->isManagement();
        $canViewClients = $user->isAdmin() || $user->isManagement() || $user->hasRole('marketing_officer');
        $canManageGroups = $user->isAdmin() || $user->isManagement() || $user->isLoanOfficer() || $user->hasRole('marketing_officer');
        $canManageFinance = $user->isAdmin() || $user->isManagement() || $user->isFinance();
        $canManageHR = $user->isAdmin() || $user->isManagement() || $user->isHR();
        $isClient = $user->isClient();
    @endphp

    <div class="text-center py-4 border-bottom border-white border-opacity-25">
        <h5 class="text-white mb-0">
            <i class="bi bi-building"></i>
            <span class="ms-2" id="logo-text">ArBif System</span>
        </h5>
    </div>
    <div class="px-3 py-4 border-bottom border-white border-opacity-25">
        <div class="d-flex align-items-center">
            <div class="user-avatar me-3">
                {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
            </div>
            <div class="user-info d-flex flex-column">
                <h6 class="mb-0 text-white">{{ $user->username }}</h6>
                <small class="text-light">
                    @php
                        $role = $user->role;
                        $roleClass = 'badge-' . str_replace('_', '-', strtolower($role));
                    @endphp
                    <!-- <span class="badge {{ $roleClass }} role-badge">
                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                    </span> -->
                </small>
            </div>
        </div>
    </div>

    <nav class="nav flex-column p-3">
        @can('view-reporting-side')
        <a href="{{ route('reportingside') }}" class="nav-link-custom reportingside {{ Request::is('reportingside*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow me-1"></i> Reports
        </a>              
        @endcan
        <hr class="dropdown-divider my-2 border-white border-opacity-25">
            <a href="{{ route('daily_collections.index') }}" class="nav-link {{ Request::is('daily_collections*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>Daily Collections</span>
            </a>
        <hr class="dropdown-divider my-2 border-white border-opacity-25">
        
        <a href="{{ route('profile.show') }}" class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>My Profile</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" 
                class="nav-link text-start w-100" style="background: none; border: none;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
    