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
    @can('view-working-side')
    <a href="{{ route('workingside') }}" 
       class="nav-link {{ Request::is('workingside*') ? 'active' : '' }}">
        <i class="fas fa-tasks me-1"></i> 
        <span>Working Side</span>
    </a>
    @endcan
    @can('view-employee-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Employee Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu">
            <div class="ps-4 mt-2">
                @can('register-employees')
                <a href="{{ route('employeeinfo') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Company Employees</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

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
    