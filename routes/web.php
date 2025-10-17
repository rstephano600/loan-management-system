
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ProfileController;

Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

use App\Http\Controllers\User\UserController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/toggle-lock', [UserController::class, 'toggleLock'])->name('users.toggle-lock');
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

});



use App\Http\Controllers\Dashboard\DashboardController;
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Example dashboard routes per role
    Route::get('/admin/dashboard', fn() => view('dashboards.admin'))->name('admin.dashboard');
    Route::get('/director/dashboard', fn() => view('dashboards.director'))->name('director.dashboard');
    Route::get('/ceo/dashboard', fn() => view('dashboards.ceo'))->name('ceo.dashboard');
    Route::get('/shareholders/dashboard', fn() => view('dashboards.shareholders'))->name('shareholders.dashboard');
    Route::get('/manager/dashboard', fn() => view('dashboards.manager'))->name('manager.dashboard');
    Route::get('/marketingofficer/dashboard', fn() => view('dashboards.marketingofficer'))->name('marketingofficer.dashboard');
    Route::get('/hr/dashboard', fn() => view('dashboards.hr'))->name('hr.dashboard');
    Route::get('/accountant/dashboard', fn() => view('dashboards.accountant'))->name('accountant.dashboard');
    Route::get('/secretary/dashboard', fn() => view('dashboards.secretary'))->name('secretary.dashboard');
    Route::get('/loanofficer/dashboard', fn() => view('dashboards.loanofficer'))->name('loanofficer.dashboard');
    Route::get('/client/dashboard', fn() => view('dashboards.client'))->name('client.dashboard');
    Route::get('/user/dashboard', fn() => view('dashboards.user'))->name('user.dashboard');
});

use App\Http\Controllers\Employee\EmployeeManagementController;


Route::middleware(['auth'])->group(function () {
    Route::resource('employees', EmployeeManagementController::class);

    Route::post('employees/{employee}/toggle-status', [EmployeeManagementController::class, 'toggleStatus'])
        ->name('employees.toggle-status');
    Route::post('employees/{employee}/referees', [EmployeeManagementController::class, 'storeReferee'])
        ->name('employees.referees.store');
    Route::delete('referees/{referee}', [EmployeeManagementController::class, 'deleteReferee'])
        ->name('referees.destroy');

    Route::get('/search-officers', [EmployeeManagementController::class, 'searchOfficers'])->name('search.officers');

});

use App\Http\Controllers\Group\GroupController;
Route::middleware(['auth'])->group(function () {
Route::resource('groups', GroupController::class);

});
use App\Http\Controllers\Group\GroupMemberController;
Route::middleware(['auth'])->group(function () {
Route::get('/groups/{group}/members/create', [GroupMemberController::class, 'create'])->name('group_members.create');
Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])->name('group_members.store');
Route::delete('/group_members/{member}', [GroupMemberController::class, 'destroy'])->name('group_members.destroy');
});

use App\Http\Controllers\Client\ClientController;
Route::middleware(['auth'])->group(function () {
Route::resource('clients', ClientController::class);
Route::get('/clients/{client}/export', [ClientController::class, 'export'])->name('clients.export');
Route::resource('guarantors', App\Http\Controllers\Client\ClientGuarantorController::class);
});

use App\Http\Controllers\Group\GroupCenterController;
Route::middleware(['auth'])->group(function () {
Route::resource('group_centers', GroupCenterController::class);
});

use App\Http\Controllers\Loan\LoanCategoryController;
use App\Http\Controllers\Loan\LoanPaymentController;
use App\Http\Controllers\Loan\LoanController;
Route::middleware(['auth'])->group(function () {
Route::resource('loan_categories', LoanCategoryController::class);
Route::patch('loan_categories/{loanCategory}/toggle', [LoanCategoryController::class, 'toggleStatus'])
     ->name('loan_categories.toggle');
Route::resource('loans', LoanController::class);
Route::post('/loans/{id}/preclosure/set', [LoanController::class, 'setPreclosureFee'])->name('loans.preclosure.set');
Route::post('/loans/{id}/preclosure/pay', [LoanController::class, 'markPreclosurePaid'])->name('loans.preclosure.pay');
Route::post('/loans/{id}/refunding/set', [LoanController::class, 'setRefund'])->name('loans.refund.set');

Route::resource('loan_payments', LoanPaymentController::class);
});

use App\Http\Controllers\Employee\EmployeeExportController;
Route::middleware(['auth'])->group(function () {
    // Employee Export Routes
    Route::get('employees/export', [EmployeeExportController::class, 'exportOptions'])
        ->name('employees.export.options');
    Route::post('employees/export/pdf', [EmployeeExportController::class, 'exportPdf'])
        ->name('employees.export.pdf');
    Route::post('employees/export/excel', [EmployeeExportController::class, 'exportExcel'])
        ->name('employees.export.excel');
    Route::post('employees/export/csv', [EmployeeExportController::class, 'exportCsv'])
        ->name('employees.export.csv');
});

// NEW CLIENT REQUST CONTROLLER

use App\Http\Controllers\Loan\LoanRequestNewClientController;
use App\Http\Controllers\Loan\LoanRequestContinuengClientController;
use App\Http\Controllers\Loan\LoanOfficerLoansController;
Route::middleware(['auth'])->group(function () {
Route::resource('loan_request_continueng_client', LoanRequestContinuengClientController::class)
    ->parameters(['loan_request_continueng_client' => 'loan']);;

Route::resource('loan_request_new_client', LoanRequestNewClientController::class)
    ->parameters(['loan_request_new_client' => 'loan']);

Route::resource('loan-pfficers-loans', LoanOfficerLoansController::class)
    ->parameters(['loan-pfficers-loans' => 'loan']);
});


use App\Http\Controllers\Loan\LoanApprovalController;

Route::prefix('loan-approvals')->middleware(['auth'])->group(function () {
    Route::get('loan-approvals/', [LoanApprovalController::class, 'index'])->name('loan-approvals.index');
    Route::get('loan-approvals/{id}', [LoanApprovalController::class, 'show'])->name('loan-approvals.show');
    Route::post('loan-approvals/{id}/approve', [LoanApprovalController::class, 'approve'])->name('loan-approvals.approve');
    Route::post('loan-approvals/{id}/reject', [LoanApprovalController::class, 'reject'])->name('loan-approvals.reject');
});

use App\Http\Controllers\Loan\RepaymentScheduleController;
Route::middleware(['auth'])->group(function () {
Route::get('/repayments/{loan}', [RepaymentScheduleController::class, 'show'])->name('repayment_schedules.show');
Route::post('/repayments/pay/{id}', [RepaymentScheduleController::class, 'pay'])->name('repayments.pay');
Route::post('/schedules/{id}/penalty', [RepaymentScheduleController::class, 'addPenalty'])->name('schedules.addPenalty');
});
// Route::get('/repayments/{loan}', [RepaymentScheduleController::class, 'show'])->name('repayment_schedules.show');

use App\Http\Controllers\Donation\DonationController;
use App\Http\Controllers\Expense\ExpenseCategoryController;
use App\Http\Controllers\Expense\ExpenseController;
use App\Http\Controllers\Salary\SalaryLevelController;
use App\Http\Controllers\Salary\EmployeeSalaryController;
use App\Http\Controllers\Salary\EmployeeSalaryPaymentController;
Route::middleware(['auth'])->group(function () {
Route::resource('donations', DonationController::class);

Route::resource('expense-categories', ExpenseCategoryController::class);

Route::resource('expenses', ExpenseController::class);
Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
Route::get('/expenses/export-excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
Route::get('/expenses/export-pdf', [ExpenseController::class, 'exportPDF'])->name('expenses.export.pdf');

Route::resource('salary_levels', SalaryLevelController::class);

Route::resource('employee_salaries', EmployeeSalaryController::class);
Route::get('/employees/search', [EmployeeSalaryController::class, 'searchEmployees'])->name('employees.search');

Route::resource('employee_salary_payments', EmployeeSalaryPaymentController::class);

});


Route::prefix('employee-payments')->name('employee_payments.')->group(function () {
    Route::get('/', [EmployeeSalaryPaymentController::class, 'index'])->name('index');
    Route::get('/{id}/create', [EmployeeSalaryPaymentController::class, 'create'])->name('create');
    Route::post('/', [EmployeeSalaryPaymentController::class, 'store'])->name('store');
    Route::get('/{id}', [EmployeeSalaryPaymentController::class, 'show'])->name('show');
    Route::delete('/{id}', [EmployeeSalaryPaymentController::class, 'destroy'])->name('destroy');
});



use App\Http\Controllers\Loan\ClientLoanController;
Route::middleware(['auth'])->group(function () {
Route::resource('client_loans', ClientLoanController::class);
Route::post('client_loans/{clientLoan}/close', [ClientLoanController::class, 'closeLoan'])->name('client_loans.close');
});

use App\Http\Controllers\Loan\DailyCollectionController;

Route::middleware(['auth'])->group(function () {
    Route::resource('daily_collections', DailyCollectionController::class);
});

Route::middleware(['auth'])->group(function () {
Route::resource('client-loan-photos', App\Http\Controllers\Loan\ClientLoanPhotoController::class);
});
// Add these routes to your web.php file

use App\Http\Controllers\Loan\LoanDashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/loansAnalysis/dashboard', [LoanDashboardController::class, 'index'])->name('loans_dashboard.dashboard');
    Route::get('/loansAnalysis/export', [LoanDashboardController::class, 'export'])->name('loans_export');
    Route::get('/loans/export/excel', [LoanDashboardController::class, 'exportExcel'])->name('loans.export.excel');
    Route::get('/loans/export/pdf', [LoanDashboardController::class, 'exportPdf'])->name('loans.export.pdf');
});



use App\Http\Controllers\Loan\LoanReportController;
use App\Http\Controllers\Loan\CollectionSummaryController;


Route::middleware('auth')->group(function () {
    Route::get('/collections/summary', [CollectionSummaryController::class, 'index'])->name('collections.summary.index');
    Route::get('/collections/summary/export/excel', [CollectionSummaryController::class, 'exportExcel'])->name('collections.summary.export.excel');
    Route::get('/collections/summary/export/pdf', [CollectionSummaryController::class, 'exportPdf'])->name('collections.summary.export.pdf');
    Route::get('/collections/summary/export/pdfwithnodata', [CollectionSummaryController::class, 'exportPdfWithNoData'])->name('collections.summary.export.pdfwithnodata');
});


Route::prefix('reports/loans')->name('reports.loans.')->group(function () {
    // Main report page
    Route::get('/', [LoanReportController::class, 'index'])->name('index');
    
    // Trending data for graph (AJAX)
    Route::get('/trending-data', [LoanReportController::class, 'getTrendingData'])->name('trending');
    
    // Export routes
    Route::get('/export/excel', [LoanReportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [LoanReportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/print', [LoanReportController::class, 'print'])->name('print');
});

// API routes for cascading dropdowns
Route::prefix('api')->group(function () {
    Route::get('/groups-by-center/{centerId}', [LoanReportController::class, 'getGroupsByCenter']);
    Route::get('/clients-by-group/{groupId}', [LoanReportController::class, 'getClientsByGroup']);
});


// Add these routes to your routes/web.php file


// use App\Http\Controllers\Loan\LoanCategoryController;

// Route::post('loan-categories/{loan_category}/toggle-status', [LoanCategoryController::class, 'toggleStatus'])
//     ->name('loan_categories.toggleStatus');
// Route::resource('loan_categories', LoanCategoryController::class);


// use App\Http\Controllers\Loan\LoanController;
// Route::resource('loans', LoanController::class);


// use App\Http\Controllers\Loan\RepaymentScheduleController;
// Route::get('loans/{loan}/schedule', [RepaymentScheduleController::class, 'index'])->name('schedules.index');
// Route::get('schedules/{repaymentSchedule}', [RepaymentScheduleController::class, 'show'])->name('schedules.show');

// use App\Http\Controllers\Loan\PaymentController;
// Route::get('loans/{loan}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
// Route::post('loans/{loan}/payments', [PaymentController::class, 'store'])->name('payments.store');