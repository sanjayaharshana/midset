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
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\ReporterController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LanguageController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Language switching routes
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/api/language/current', [LanguageController::class, 'current'])->name('language.current');
Route::get('/api/language/available', [LanguageController::class, 'available'])->name('language.available');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OAuth routes
Route::get('/auth/xelenic', [AuthController::class, 'redirectToXelenic'])->name('auth.xelenic');
Route::get('/callback', [AuthController::class, 'handleXelenicCallback'])->name('auth.xelenic.callback');

// Debug route for OAuth testing
Route::get('/debug/oauth', function () {
    $config = config('services.xelenic');
    return response()->json([
        'client_id' => $config['client_id'] ?? 'Not set',
        'client_secret' => $config['client_secret'] ? 'Set' : 'Not set',
        'redirect' => $config['redirect'] ?? 'Not set',
        'base_url' => $config['base_url'] ?? 'Not set',
    ]);
});

// Admin routes
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User Management routes
    Route::resource('admin/users', UserManagementController::class)->names('admin.users');

    // Role Management routes
    Route::resource('admin/roles', RoleManagementController::class)->names('admin.roles');

    // Brand Management routes
    Route::resource('admin/clients', ClientController::class)->names('admin.clients');

    // Job Management routes
    Route::resource('admin/jobs', JobController::class)->names('admin.jobs');
    Route::post('admin/jobs/{job}/update-settings', [JobController::class, 'updateSettings'])->name('admin.jobs.update-settings');
    Route::post('admin/jobs/{job}/update-allowance-rules', [JobController::class, 'updateAllowanceRules'])->name('admin.jobs.update-allowance-rules');

    // Promoter Management routes
    Route::resource('admin/promoters', PromoterController::class)->names('admin.promoters');
    Route::get('admin/promoters/ajax/search', [PromoterController::class, 'ajaxSearch'])->name('admin.promoters.ajax.search');
    Route::resource('admin/coordinators', CoordinatorController::class)->names('admin.coordinators');
    Route::get('admin/coordinators/ajax/search', [CoordinatorController::class, 'ajaxSearch'])->name('admin.coordinators.ajax.search');
    Route::post('admin/promoters/import-csv', [PromoterController::class, 'importCsv'])->name('admin.promoters.import-csv');
    Route::get('admin/promoters/{promoter}/salary-slip/{itemId}/print', [PromoterController::class, 'printSalarySlip'])->name('admin.promoters.salary-slip.print');

    // Promoter Position Management routes
    Route::resource('admin/promoter-positions', PromoterPositionController::class)->names('admin.promoter-positions');

    // Coordinator Management routes
    Route::resource('admin/coordinators', CoordinatorController::class)->names('admin.coordinators');

    // Salary Sheet Management routes
    // Custom routes must be defined before resource route to avoid conflicts
    Route::get('admin/salary-sheets/by-job/{jobId}', [SalarySheetController::class, 'getByJob'])->name('admin.salary-sheets.by-job');
    Route::get('admin/salary-sheets/{salarySheet}/print', [SalarySheetController::class, 'print'])->name('admin.salary-sheets.print');
    Route::post('admin/salary-sheets/{salarySheet}/approve', [SalarySheetController::class, 'approve'])->name('admin.salary-sheets.approve');
    Route::resource('admin/salary-sheets', SalarySheetController::class)->names('admin.salary-sheets');

    // Position Wise Salary Rules routes
    Route::get('admin/position-wise-salary-rules/get-rules', [PositionWiseSalaryRuleController::class, 'getRules'])->name('admin.position-wise-salary-rules.get-rules');
    Route::post('admin/position-wise-salary-rules/store-multiple', [PositionWiseSalaryRuleController::class, 'storeMultiple'])->name('admin.position-wise-salary-rules.store-multiple');
    Route::resource('admin/position-wise-salary-rules', PositionWiseSalaryRuleController::class)->names('admin.position-wise-salary-rules');

    // Allowance Management routes
    Route::resource('admin/allowances', AllowanceController::class)->names('admin.allowances');

    // Reporter Management routes
    Route::resource('admin/reporters', ReporterController::class)->names('admin.reporters');

    // Officer Management routes
    Route::resource('admin/officers', OfficerController::class)->names('admin.officers');

    // Settings Management routes
    Route::get('admin/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::get('admin/settings/group/{group}', [SettingsController::class, 'getByGroup'])->name('admin.settings.group');
    Route::get('admin/settings/get/{key}', [SettingsController::class, 'get'])->name('admin.settings.get');
    
    // Dynamic Settings API routes
    Route::post('admin/settings/create', [SettingsController::class, 'create'])->name('admin.settings.create');
    Route::delete('admin/settings/{key}', [SettingsController::class, 'destroy'])->name('admin.settings.destroy');
    Route::get('admin/settings/search', [SettingsController::class, 'search'])->name('admin.settings.search');
    Route::get('admin/settings/export', [SettingsController::class, 'export'])->name('admin.settings.export');
    Route::post('admin/settings/import', [SettingsController::class, 'import'])->name('admin.settings.import');

    Route::post('admin/salary-sheet-enforce',[SalarySheetController::class,'enforce'])->name('admin.salary.enforce');
    
    // API endpoint to generate JSON data for salary sheet
    Route::get('admin/salary-sheets/{id}/json', [SalarySheetController::class, 'generateJsonData'])->name('admin.salary-sheets.json');

});
