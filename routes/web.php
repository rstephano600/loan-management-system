
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
});

use App\Http\Controllers\Group\GroupController;
Route::middleware(['auth'])->group(function () {
Route::resource('groups', GroupController::class);

});
use App\Http\Controllers\Group\GroupMemberController;

Route::get('/groups/{group}/members/create', [GroupMemberController::class, 'create'])->name('group_members.create');
Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])->name('group_members.store');
Route::delete('/group_members/{member}', [GroupMemberController::class, 'destroy'])->name('group_members.destroy');

use App\Http\Controllers\Client\ClientController;
Route::resource('clients', ClientController::class);
Route::get('/clients/{client}/export', [ClientController::class, 'export'])->name('clients.export');
Route::resource('guarantors', App\Http\Controllers\Client\ClientGuarantorController::class);

use App\Http\Controllers\Group\GroupCenterController;
Route::resource('group_centers', GroupCenterController::class);


use App\Http\Controllers\Loan\LoanCategoryController;
use App\Http\Controllers\Loan\LoanPaymentController;
use App\Http\Controllers\Loan\LoanController;

Route::resource('loan_categories', LoanCategoryController::class);
Route::patch('loan_categories/{loanCategory}/toggle', [LoanCategoryController::class, 'toggleStatus'])
     ->name('loan_categories.toggle');
Route::resource('loans', LoanController::class);
Route::post('/loans/{id}/preclosure/set', [LoanController::class, 'setPreclosureFee'])->name('loans.preclosure.set');
Route::post('/loans/{id}/preclosure/pay', [LoanController::class, 'markPreclosurePaid'])->name('loans.preclosure.pay');

Route::resource('loan_payments', LoanPaymentController::class);


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
Route::resource('loan_request_continueng_client', LoanRequestContinuengClientController::class);

Route::resource('loan_request_new_client', LoanRequestNewClientController::class)
    ->names([
        'index' => 'loan_request_new_client.index',
        'create' => 'loan_request_new_client.create',
        'store' => 'loan_request_new_client.store',
        'show' => 'loan_request_new_client.show',
        'edit' => 'loan_request_new_client.edit', // This is the crucial line
        'update' => 'loan_request_new_client.update',
        'destroy' => 'loan_request_new_client.destroy',
    ]);

use App\Http\Controllers\Loan\LoanApprovalController;

Route::prefix('loan-approvals')->middleware(['auth'])->group(function () {
    Route::get('loan-approvals/', [LoanApprovalController::class, 'index'])->name('loan-approvals.index');
    Route::get('loan-approvals/{id}', [LoanApprovalController::class, 'show'])->name('loan-approvals.show');
    Route::post('loan-approvals/{id}/approve', [LoanApprovalController::class, 'approve'])->name('loan-approvals.approve');
    Route::post('loan-approvals/{id}/reject', [LoanApprovalController::class, 'reject'])->name('loan-approvals.reject');
});

use App\Http\Controllers\Loan\RepaymentScheduleController;
Route::get('/repayments/{loan}', [RepaymentScheduleController::class, 'show'])->name('repayment_schedules.show');
Route::post('/repayments/pay/{id}', [RepaymentScheduleController::class, 'pay'])->name('repayments.pay');
Route::post('/schedules/{id}/penalty', [RepaymentScheduleController::class, 'addPenalty'])->name('schedules.addPenalty');

// Route::get('/repayments/{loan}', [RepaymentScheduleController::class, 'show'])->name('repayment_schedules.show');

use App\Http\Controllers\Donation\DonationController;
Route::resource('donations', DonationController::class);

use App\Http\Controllers\Expense\ExpenseCategoryController;
Route::resource('expense-categories', ExpenseCategoryController::class);

use App\Http\Controllers\Expense\ExpenseController;
Route::resource('expenses', ExpenseController::class);

use App\Http\Controllers\Salary\SalaryLevelController;
Route::resource('salary_levels', SalaryLevelController::class);

use App\Http\Controllers\Salary\EmployeeSalaryController;
Route::resource('employee_salaries', EmployeeSalaryController::class);

use App\Http\Controllers\Salary\EmployeeSalaryPaymentController;
Route::prefix('employee-payments')->name('employee_payments.')->group(function () {
    Route::get('/', [EmployeeSalaryPaymentController::class, 'index'])->name('index');
    Route::get('/{id}/create', [EmployeeSalaryPaymentController::class, 'create'])->name('create');
    Route::post('/', [EmployeeSalaryPaymentController::class, 'store'])->name('store');
    Route::get('/{id}', [EmployeeSalaryPaymentController::class, 'show'])->name('show');
    Route::delete('/{id}', [EmployeeSalaryPaymentController::class, 'destroy'])->name('destroy');
});



use App\Http\Controllers\Loan\ClientLoanController;
Route::resource('client_loans', ClientLoanController::class);
Route::post('client_loans/{clientLoan}/close', [ClientLoanController::class, 'closeLoan'])->name('client_loans.close');

use App\Http\Controllers\Loan\DailyCollectionController;

Route::middleware(['auth'])->group(function () {
    Route::resource('daily_collections', DailyCollectionController::class);
});
Route::resource('client-loan-photos', App\Http\Controllers\Loan\ClientLoanPhotoController::class);

// Add these routes to your web.php file

use App\Http\Controllers\Loan\LoanDashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/loansAnalysis/dashboard', [LoanDashboardController::class, 'index'])->name('loans_dashboard.dashboard');
    Route::get('/loansAnalysis/export', [LoanDashboardController::class, 'export'])->name('loans_export');
    Route::get('/loans/export/excel', [LoanDashboardController::class, 'exportExcel'])->name('loans.export.excel');
    Route::get('/loans/export/pdf', [LoanDashboardController::class, 'exportPdf'])->name('loans.export.pdf');
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