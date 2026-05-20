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

    @php
    $user         = Auth::user();
    $isAdmin      = $user->isAdmin();
    $isManagement = $user->isManagement();
    $isLoanOfficer= $user->isLoanOfficer();
    $isFinance    = $user->isFinance();
    $isHR         = $user->isHR();
    $isClient     = $user->isClient();
    $isMarketing  = $user->hasRole('marketing_officer');

    // Derived permission groups
    $canAdmin     = $isAdmin;
    $canLoans     = $isAdmin || $isManagement;
    $canClients   = $isAdmin || $isManagement || $isMarketing;
    $canGroups    = $isAdmin || $isManagement || $isLoanOfficer || $isMarketing;
    $canFinance   = $isAdmin || $isManagement || $isFinance;
    $canHR        = $isAdmin || $isManagement || $isHR;
    @endphp


    <div class="text-center py-4 border-bottom border-white border-opacity-25">
        <h5 class="text-white mb-0">
            <i class="fas fa-building"></i>
            <span class="ms-2" id="logo-text">ArBif System</span>
        </h5>
    </div>

<nav class="nav flex-column p-3">
    
    {{-- Using Bootstrap Icons (bi bi-*) --}}
    @can('view-confirguration-side')
    <a href="{{ route('configurationside') }}" 
       class="nav-link {{ Request::is('configurationside*') ? 'active' : '' }}">
        <i class="fas fa-sliders me-1"></i> 
        <span>Configuration</span>
    </a>
    @endcan

    {{-- Mixed usage example --}}
    @can('view-accounting-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu" 
           class="nav-link" 
           data-bs-toggle="collapse" 
           role="button">
            <i class="fas fa-book me-2"></i> 
            <span>Accounting Menu</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        
        <div class="collapse" id="accountingSubmenu">
            <div class="ps-4 mt-2">
                @can('view-country')
                <a href="{{ route('accountCountry') }}" 
                   class="nav-link d-flex align-items-center">
                    <i class="fas fa-flag me-2"></i> 
                    <span>Country Accounts</span>
                </a>
                @endcan
                @can('view-country')
                <a href="{{ route('accountBusiness') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Company Branch</span></a>
                @endcan
                @can('view-accounting-codes')
                <a href="{{ route('accountRoot') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Root Accounts</span></a>
                <a href="{{ route('accountFirstBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Subu-Root Accounts</span></a>
                <a href="{{ route('accountSecondBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Control (Accounts Types)</span></a>
                <a href="{{ route('accountSecondBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Centres (Centre Accounts)</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- Mixed usage example --}}
    @can('view-permission-access-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu2" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-book me-2"></i> <span>Permission Access Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="accountingSubmenu2">
            <div class="ps-4 mt-2">
                @can('assign-permission-access')
                <a href="{{ route('usersRole') }}" class="nav-link d-flex align-items-center"><i class="fas fa-flag me-2"></i> <span>Assign Permissions</span></a>
                @endcan
                @if($canAdmin)
                @can('assign-permission-access')
                <a href="{{ route('usersRole') }}" class="nav-link d-flex align-items-center"><i class="fas fa-flag me-2"></i> <span>Assign Permissions Admin</span></a>
                @endcan
                @endif
            </div>
        </div>
    </div>
    @endcan

    @can('view-system-users-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu3" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-book me-2"></i> <span>System Users Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="accountingSubmenu3">
            <div class="ps-4 mt-2">
                @can('view-system-users')
                <a href="{{ route('systemUsers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-flag me-2"></i> <span>System Users</span></a>
                @endcan
                @if($canAdmin)
                @can('view-system-users')
                <a href="{{ route('systemUsers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-flag me-2"></i> <span>System Users</span></a>
                @endcan
                @endif
            </div>
        </div>
    </div>
    @endcan


    <hr class="dropdown-divider my-3">

    {{-- Profile with Bootstrap Icon --}}
    <a href="{{ route('profile.show') }}" 
       class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
        <i class="bi bi-person-circle me-2"></i> 
        <span>My Profile</span>
    </a>

    {{-- Logout with Font Awesome --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button type="submit" class="nav-link text-start w-100" 
                style="background: none; border: none;">
            <i class="fas fa-sign-out-alt me-2"></i> 
            <span>Logout</span>
        </button>
    </form>
</nav>

<style>
.nav-link {
    padding: 10px 15px;
    color: rgba(255, 255, 255, 0.75);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.nav-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.nav-link.active {
    color: #fff;
    background: linear-gradient(90deg, rgba(13, 110, 253, 0.2), transparent);
    border-left: 3px solid #0d6efd;
}

.nav-link i:first-child {
    width: 20px;
    text-align: center;
    transition: transform 0.3s;
}

.nav-link:hover i:first-child {
    transform: scale(1.1);
}

.transition-toggle {
    transition: transform 0.3s;
}

.nav-link[aria-expanded="true"] .transition-toggle {
    transform: rotate(180deg);
}

.collapse .nav-link {
    padding: 8px 15px;
    font-size: 0.9rem;
}

.collapse .nav-link:hover {
    padding-left: 20px !important;
}

button.nav-link {
    background: none;
    border: none;
    cursor: pointer;
}

button.nav-link:hover {
    background: rgba(220, 53, 69, 0.15);
    color: #ff6b6b;
}
</style>
</aside>
<script>
    // Force re-initialize all collapses
$(document).ready(function() {
    // Initialize all collapses manually
    $('[data-bs-toggle="collapse"]').each(function() {
        const target = $(this).data('bs-target');
        if (target) {
            $(target).collapse({
                toggle: false
            });
        }
    });
    
    // Handle click manually
    $('[data-bs-toggle="collapse"]').off('click').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('bs-target');
        if (target) {
            $(target).collapse('toggle');
            
            // Rotate chevron
            const chevron = $(this).find('.fa-chevron-down, .bi-chevron-down');
            if (chevron.length) {
                chevron.css('transform', $(target).hasClass('show') ? 'rotate(0deg)' : 'rotate(180deg)');
            }
        }
    });
});
</script>