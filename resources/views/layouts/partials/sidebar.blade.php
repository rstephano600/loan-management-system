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
        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>


        <!-- LOAN OFFICERS -->

@if($isLoanOfficer)
 <h5 class="mb-0 text-dark">Collections</h5>
            <hr class="dropdown-divider my-2 border-white border-opacity-25">
            <a href="{{ route('collections.summary.index') }}" class="nav-link {{ Request::is('collections.summary.index*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i>
                <span>Todays Collection</span>
            </a>

 <h5 class="mb-0 text-dark">Loan Reqeust</h5>
            <a href="{{ route('loan_request_continueng_client.index') }}" class="nav-link {{ Request::is('loan_request_continueng_client*') ? 'active' : '' }}">
                <i class="bi bi-person-check"></i>
                <span>Continuing Client Loans</span>
            </a>

            <a href="{{ route('loan_request_new_client.index') }}" class="nav-link {{ Request::is('loan_request_new_client*') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i>
                <span>New Client Loans</span>
            </a>

<h5 class="mb-0 text-dark">Your Loans</h5>
            <a href="{{ route('loans.index') }}" class="nav-link {{ Request::is('loans') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>All Loans</span>
            </a>
            
<h5 class="mb-0 text-dark">Your Loans</h5>
            <a href="{{ route('clients.index') }}" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i>
                <span>All Clients</span>
            </a>

<h5 class="mb-0 text-dark">Groups and Centers</h5>
            <a href="{{ route('groups.index') }}" class="nav-link {{ Request::is('groups*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Groups</span>
            </a>
            <a href="{{ route('group_centers.index') }}" class="nav-link {{ Request::is('group_centers*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>
                <span>Groups Center</span>
            </a>

@endif

        @if($canManageAll)
            <hr class="dropdown-divider my-2 border-white border-opacity-25">
             <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>System Users</span>
         </a>
        @endif

        @if($canManageHisData)


        @endif

        @if($canManageGroups)
            <a href="{{ route('groups.index') }}" class="nav-link {{ Request::is('groups*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Groups</span>
            </a>
            <a href="{{ route('group_centers.index') }}" class="nav-link {{ Request::is('group_centers*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>
                <span>Groups Center</span>
            </a>
        @endif
        
        @if($canManageLoans)
            <hr class="dropdown-divider my-2 border-white border-opacity-25">
            <a href="{{ route('loans.index') }}" class="nav-link {{ Request::is('loans') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>All Loans</span>
            </a>

            <a href="{{ route('loan_request_continueng_client.index') }}" class="nav-link {{ Request::is('loan_request_continueng_client*') ? 'active' : '' }}">
                <i class="bi bi-person-check"></i>
                <span>Continuing Client Loans</span>
            </a>

            <a href="{{ route('loan_request_new_client.index') }}" class="nav-link {{ Request::is('loan_request_new_client*') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i>
                <span>New Client Loans</span>
            </a>

            <a href="{{ route('loan-approvals.index') }}" class="nav-link {{ Request::is('loan-approvals*') ? 'active' : '' }}">
                <i class="bi bi-check2-circle"></i>
                <span>Client Loans Approval</span>
            </a>

             <a href="{{ route('loan_categories.index') }}" class="nav-link {{ Request::is('loan_categories*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Loans categories</span>
            </a>
            <a href="{{ route('client-loan-photos.index') }}" class="nav-link {{ Request::is('client-loan-photos*') ? 'active' : '' }}">
                <i class="bi bi-image"></i>
                <span>Client loan photos</span>
            </a>
            <a href="{{ route('loans_dashboard.dashboard') }}" class="nav-link {{ Request::is('loans_dashboard*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i>
                <span>Loans Dashboard</span>
            </a>
        @endif

        @if($canViewClients)
            <hr class="dropdown-divider my-2 border-white border-opacity-25">
             <a href="{{ route('clients.index') }}" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i>
                <span>All Clients</span>
            </a>
            <a href="{{ route('collections.summary.index') }}" class="nav-link {{ Request::is('collections.summary.index*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i>
                <span>Todays Collection</span>
            </a>
        @endif
        

        @if($canManageFinance)
            <hr class="dropdown-divider my-2 border-white border-opacity-25">
            <a href="{{ route('daily_collections.index') }}" class="nav-link {{ Request::is('daily_collections*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span>Daily Collections</span>
            </a>
            <a href="{{ route('expenses.index') }}" class="nav-link {{ Request::is('expenses*') ? 'active' : '' }}">
                <i class="bi bi-arrow-up-right-square"></i>
                <span>Expenses</span>
            </a>
            <a href="{{ route('expense-categories.index') }}" class="nav-link {{ Request::is('expense-categories*') ? 'active' : '' }}">
                <i class="bi bi-card-list"></i>
                <span>Expense Categories</span>
            </a>
             <a href="{{ route('donations.index') }}" class="nav-link {{ Request::is('donations*') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>Donations & Supports</span>
            </a>
        @endif

        @if($canManageHR)
             <hr class="dropdown-divider my-2 border-white border-opacity-25">
            <a href="{{ route('reports.loans.index') }}" class="nav-link {{ Request::is('reports.loans*') ? 'active' : '' }}">
                <i class="bi bi-chart"></i>
                <span>Loan Reports</span>
            </a>
            <a href="{{ route('employees.index') }}" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i>
                <span>Employees</span>
            </a>
            <a href="{{ route('employee_salary_payments.index') }}" class="nav-link {{ Request::is('employee_salary_payments*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                <span>Employee Salaries Payments</span>
            </a>
            <a href="{{ route('employee_salaries.index') }}" class="nav-link {{ Request::is('employee_salaries*') ? 'active' : '' }}">
                <i class="bi bi-cash"></i>
                <span>Employee Salaries</span>
            </a>
            <a href="{{ route('salary_levels.index') }}" class="nav-link {{ Request::is('salary_levels*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i>
                <span>Salary Levels</span>
            </a>
        @endif 

        
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