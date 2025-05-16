<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminTenantController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\CentralAdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AdminUpdaterController;

// Test database connection
Route::get('/test-connection', function () {
    try {
        DB::connection('tenant')->getPdo();
        return 'Connection successful!';
    } catch (\Exception $e) {
        return 'Connection failed: ' . $e->getMessage();
    }
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('tenants/create', [AdminTenantController::class, 'create'])->name('admin.tenants.create');
    Route::post('tenants', [AdminTenantController::class, 'store'])->name('admin.tenants.store');
    Route::get('tenants/{tenant}/edit', [AdminTenantController::class, 'edit'])->name('admin.tenants.edit');
    Route::patch('tenants/{tenant}', [AdminTenantController::class, 'update'])->name('admin.tenants.update');
    Route::post('tenants/{tenant}/accept', [AdminTenantController::class, 'accept'])->name('admin.tenants.accept');
    Route::delete('tenants/{tenant}', [AdminTenantController::class, 'delete'])->name('admin.tenants.delete');
    Route::patch('tenants/{tenant}/revert', [AdminTenantController::class, 'revert'])->name('admin.tenants.revert');

    // Admin updater routes
    Route::get('updater', [AdminUpdaterController::class, 'showUpdater'])->name('admin.updater');
    Route::get('updater/check', [AdminUpdaterController::class, 'checkForUpdates'])->name('admin.updater.check');
    Route::post('updater/update', [AdminUpdaterController::class, 'performUpdate'])->name('admin.updater.perform');
});

// Tenant self-registration
Route::get('tenant/register', [TenantRegistrationController::class, 'showRegistrationForm'])->name('tenant.register');
Route::post('tenant/register', [TenantRegistrationController::class, 'register']);


use App\Http\Middleware\StoreTenantInSession;

// Tenant login routes with StoreTenantInSession middleware to capture tenant query param
Route::middleware([StoreTenantInSession::class])->group(function () {
    Route::get('login', [TenantAuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('login', [TenantAuthController::class, 'login']);
    Route::post('logout', [TenantAuthController::class, 'logout'])->name('tenant.logout')->middleware('auth:tenant');
});

use App\Http\Middleware\TenantConnection;

Route::prefix('{tenant}')->middleware(['tenant.connection'])->group(function () {
    Route::get('dashboard', [TenantDashboardController::class, 'dashboard'])->name('tenant.dashboard');
    Route::post('/tenant/users', [TenantDashboardController::class, 'store'])->name('tenant.users.store');
    Route::get('/tenant/pos/dashboard', [TenantDashboardController::class, 'dashboard'])->name('tenant.pos.dashboard');
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'tenantDashboard'])->name('tenant.dashboard.view');
    Route::get('reports/sales', [SaleController::class, 'salesReport'])->name('sales.report');
    Route::get('reports/products', [SaleController::class, 'productReport'])->name('products.report');
    Route::get('sales/{sale}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.invoice');
    Route::get('pos', [SaleController::class, 'index'])->name('pos.index');
    Route::get('/tenant/users/create', [TenantDashboardController::class, 'usersCreate'])->name('tenant.users.create');
    Route::get('/tenant/profile/edit', [TenantDashboardController::class, 'editProfile'])->name('tenant.profile.edit');
    Route::get('tenant/pos/index', [TenantDashboardController::class, 'dashboard'])->name('tenant.pos.index');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
});

// Google authentication
Route::get('auth/google/redirect', [TenantAuthController::class, 'redirectToGoogle'])->name('tenant.auth.google.redirect');
Route::get('auth/google/callback', [TenantAuthController::class, 'handleGoogleCallback'])->name('tenant.auth.google.callback');

// Central admin auth routes
Route::get('admin/login', [CentralAdminAuthController::class, 'showLoginForm'])->name('central.admin.login');
Route::post('admin/login', [CentralAdminAuthController::class, 'login']);
Route::post('admin/logout', [CentralAdminAuthController::class, 'logout'])->name('central.admin.logout');

// Central admin profile routes
use App\Http\Controllers\CentralAdminProfileController;
Route::get('admin/profile/edit', [CentralAdminProfileController::class, 'edit'])->name('central.admin.profile.edit');
Route::put('admin/profile/update', [CentralAdminProfileController::class, 'update'])->name('central.admin.profile.update');

// Redirect root to central admin login
Route::get('/', function () {
    return redirect()->route('central.admin.login');
});
