
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\AuthenticationController;

Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

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
use App\Http\Controllers\Group\GroupCenterController;

Route::resource('group_centers', GroupCenterController::class);


