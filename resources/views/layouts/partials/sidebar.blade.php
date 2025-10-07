
<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="text-center py-4 border-bottom border-secondary">
        <h5 class="text-white mb-0">
            <i class="bi bi-building"></i>
            <span class="ms-2" id="logo-text">ArBif System</span>
        </h5>
    </div>
    
    <!-- User Info -->
    <div class="px-3 py-4 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <div class="user-avatar me-3">
                {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
            </div>
            <div class="user-info">
                <h6 class="mb-0 text-white">{{ Auth::user()->username }}</h6>
                <small class="text-light">
                    @php
                        $user = Auth::user();
                        $role = $user->role;
                        $roleClass = 'badge-' . str_replace('_', '-', strtolower($role));
                    @endphp
                    <span class="badge {{ $roleClass }} role-badge">
                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                    </span>
                </small>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="nav flex-column p-3">
        <!-- Dashboard - All Users -->
        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        
        @php
            $user = Auth::user();
            $isAdmin = $user->role === 'admin';
            $isDirector = $user->role === 'director';
            $isCEO = $user->role === 'ceo';
            $isShareholders = $user->role === 'shareholders';
            $isManager = $user->role === 'manager';
            $isMarketingOfficer = $user->role === 'marketing_officer';
            $isHR = $user->role === 'hr';
            $isAccountant = $user->role === 'accountant';
            $isSecretary = $user->role === 'secretary';
            $isLoanOfficer = $user->role === 'loan_officer';
            $isClient = $user->role === 'client';
            
            // Permission groups
            $canManageLoans = $isAdmin || $isDirector || $isCEO || $isManager || $isLoanOfficer;
            $canViewReports = $isAdmin || $isDirector || $isCEO || $isShareholders || $isManager || $isAccountant;
            $canManageClients = $isAdmin || $isManager || $isLoanOfficer || $isMarketingOfficer;
            $canManageFinance = $isAdmin || $isDirector || $isCEO || $isManager || $isAccountant;
            $canManageEmployees = $isAdmin || $isDirector || $isCEO || $isHR || $isManager;
            $canManageSettings = $isAdmin || $isDirector || $isCEO;
        @endphp

        <a href="{{ route('employees.index') }}" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>Employees</span>
        </a>
        <a href="{{ route('groups.index') }}" class="nav-link {{ Request::is('groups*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Groups</span>
        </a>
        <a href="{{ route('clients.index') }}" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Clients</span>
        </a>
        <!-- Clients/Wateja Management -->
        @if($canManageClients)
        <a href="" class="nav-link {{ Request::is('clientss*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>Wateja</span>
        </a>
        @endif
        
        <!-- Loan Applications -->
        @if($canManageLoans)
        <a href="" class="nav-link {{ Request::is('loan-applications*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>Maombi ya Mikopo</span>
        </a>
        @endif
        
        <!-- Active Loans -->
        @if($canManageLoans)
        <a href="" class="nav-link {{ Request::is('loans*') && !Request::is('loan-applications*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i>
            <span>Mikopo</span>
        </a>
        @endif
        
        <!-- Payments/Malipo -->
        @if($canManageFinance || $isLoanOfficer)
        <a href="" class="nav-link {{ Request::is('payments*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Malipo</span>
        </a>
        @endif
        
        <!-- Loan Products -->
        @if($isAdmin || $isDirector || $isCEO || $isManager)
        <a href="" class="nav-link {{ Request::is('loan-products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Bidhaa za Mikopo</span>
        </a>
        @endif
        
        <!-- Collateral -->
        @if($canManageLoans)
        <a href="" class="nav-link {{ Request::is('collateral*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i>
            <span>Dhamana</span>
        </a>
        @endif
        
        <!-- Reports/Ripoti -->
        @if($canViewReports)
        <a href="" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i>
            <span>Ripoti</span>
        </a>
        @endif
        
        <!-- Employees/Wafanyakazi -->
        @if($canManageEmployees)
        <a href="" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <span>Wafanyakazi</span>
        </a>
        @endif
        
        <!-- Users Management -->
        @if($isAdmin || $isDirector || $isCEO || $isHR)
        <a href="" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Watumiaji</span>
        </a>
        @endif
        

        
        <!-- Settings/Mipangilio -->
        @if($canManageSettings)
        <a href="" class="nav-link {{ Request::is('settings*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Mipangilio</span>
        </a>
        @endif
        
        <!-- Client Portal -->
        @if($isClient)
        <a href="" class="nav-link {{ Request::is('client/*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            <span>Mikopo Yangu</span>
        </a>
        @endif
        
        <!-- Profile -->
        <a href="" class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Wasifu Wangu</span>
        </a>
        
        <!-- Help & Support -->

            <i class="bi bi-question-circle"></i>
            <span>Msaada</span>
        </a>

        <a href="" class="nav-link {{ Request::is('support*') ? 'active' : '' }}">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-item d-flex align-items-center">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                        </form>
</a>
    </nav>
</aside>

