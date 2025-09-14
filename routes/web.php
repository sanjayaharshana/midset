<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\PromoterPositionController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\SalarySheetController;
use App\Http\Controllers\PositionWiseSalaryRuleController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User Management routes
    Route::resource('admin/users', UserManagementController::class)->names('admin.users');

    // Role Management routes
    Route::resource('admin/roles', RoleManagementController::class)->names('admin.roles');

    // Client Management routes
    Route::resource('admin/clients', ClientController::class)->names('admin.clients');

    // Job Management routes
    Route::resource('admin/jobs', JobController::class)->names('admin.jobs');
    Route::post('admin/jobs/{job}/update-settings', [JobController::class, 'updateSettings'])->name('admin.jobs.update-settings');

    // Promoter Management routes
    Route::resource('admin/promoters', PromoterController::class)->names('admin.promoters');

    // Promoter Position Management routes
    Route::resource('admin/promoter-positions', PromoterPositionController::class)->names('admin.promoter-positions');

    // Coordinator Management routes
    Route::resource('admin/coordinators', CoordinatorController::class)->names('admin.coordinators');

    // Salary Sheet Management routes
    Route::resource('admin/salary-sheets', SalarySheetController::class)->names('admin.salary-sheets');
    Route::get('admin/salary-sheets/by-job/{jobId}', [SalarySheetController::class, 'getByJob'])->name('admin.salary-sheets.by-job');

    // Position Wise Salary Rules routes
    Route::get('admin/position-wise-salary-rules/get-rules', [PositionWiseSalaryRuleController::class, 'getRules'])->name('admin.position-wise-salary-rules.get-rules');
    Route::post('admin/position-wise-salary-rules/store-multiple', [PositionWiseSalaryRuleController::class, 'storeMultiple'])->name('admin.position-wise-salary-rules.store-multiple');
    Route::resource('admin/position-wise-salary-rules', PositionWiseSalaryRuleController::class)->names('admin.position-wise-salary-rules');

    Route::post('admin/salary-sheet-enforce',[SalarySheetController::class,'enforce'])->name('admin.salary.enforce');

});
