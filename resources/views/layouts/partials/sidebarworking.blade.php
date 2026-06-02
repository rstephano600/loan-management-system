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
                <a href="#" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Innactive Employees</span></a>
                <a href="#" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Employee Referees</span></a>
                <a href="#" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Employee Nest Of Kin</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-group-centers-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu1" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Group Centers Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu1">
            <div class="ps-4 mt-2">
                @can('view-group-centers')
                <a href="{{ route('groupCenter') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Group Centers</span></a>
                <a href="{{ route('innactivegroupCenter') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Innactive Group Centers</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-groups-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu2" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Loan Groups Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu2">
            <div class="ps-4 mt-2">
                @can('view-loan-groups')
                <a href="{{ route('centerGroups') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Loan Groups</span></a>
                <a href="{{ route('innactivecenterGroups') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Innactive Loan Groups</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-beneficiary-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu3" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Loan Beneficiary Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu3">
            <div class="ps-4 mt-2">
                @can('view-loan-beneficiary')
                <a href="{{ route('clientinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>client informations</span></a>
                <a href="{{ route('groupMembers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Beneficiary Groups</span></a>
                <a href="{{ route('innactivegroupMembers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Innactive Loan Beneficiary</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu4" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Loans Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu4">
            <div class="ps-4 mt-2">
                @can('view-loan')
                <a href="{{ route('loansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Loans Informations</span></a>
                <a href="{{ route('closedloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Closed Loans </span></a>
                <a href="{{ route('refundedloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Refunded Loans </span></a>
                <a href="{{ route('rejectedloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Rejected Loans </span></a>
                @endcan
                @can('approve-loans')
                <a href="{{ route('approveloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Approve Loans</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-categories-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu5" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Loan Category Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu5">
            <div class="ps-4 mt-2">
                @can('view-loan-categories')
                <a href="{{ route('loancategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Loan Categories</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-repayments-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu7" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Loans Repayments Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu7">
            <div class="ps-4 mt-2">
                @can('view-loan-repayments')
                <a href="{{ route('loansrepayments') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Loans Repayments</span></a>
                <a href="{{ route('loansrepaymentsfees') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Fees Repayments</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-penalty-categories-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu6" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Loan Penalities  Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu6">
            <div class="ps-4 mt-2">
                @can('view-loan-penalty-categories')
                <a href="{{ route('loanpenalties') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Loan Penalties</span></a>
                <a href="{{ route('loanpenaltycategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Penalty Categories</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-loan-guarantors-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu8" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-book me-2"></i> 
        <span>Guarantors Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu8">
            <div class="ps-4 mt-2">
                @can('view-loan-guarantors')
                <a href="{{ route('guarantors') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Client Guarantors</span></a>
                <a href="{{ route('loanguarantors') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Loan Guarantors</span></a>
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
    