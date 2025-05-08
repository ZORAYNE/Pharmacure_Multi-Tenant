<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminTenantController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\CentralAdminAuthController;
use App\Http\Controllers\AdminDashboardController;

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
    Route::post('tenants/{id}/accept', [AdminTenantController::class, 'accept'])->name('admin.tenants.accept');
    Route::delete('tenants/{id}', [AdminTenantController::class, 'delete'])->name('admin.tenants.delete');
    Route::patch('tenants/{id}/revert', [AdminTenantController::class, 'revert'])->name('admin.tenants.revert');
});

// Tenant self registration
Route::get('tenant/register', [TenantRegistrationController::class, 'showRegistrationForm'])->name('tenant.register');
Route::post('tenant/register', [TenantRegistrationController::class, 'register']);

// Tenant login routes
Route::get('login', [TenantAuthController::class, 'showLoginForm'])->name('tenant.login');
Route::post('login', [TenantAuthController::class, 'login']);
Route::post('logout', [TenantAuthController::class, 'logout'])->name('tenant.logout')->middleware('auth:tenant');

// Tenant authenticated routes
Route::get('dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard')->middleware('auth:tenant');


// Additional routes
Route::get('reports/sales', [SaleController::class, 'salesReport'])->name('sales.report');
Route::get('reports/products', [SaleController::class, 'productReport'])->name('products.report');
Route::get('sales/{sale}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.invoice');
Route::get('pos', [SaleController::class, 'index'])->name('pos.index');
Route::post('sales', [SaleController::class, 'store'])->name('sales.store');

// Google authentication
Route::get('auth/google/redirect', [TenantAuthController::class, 'redirectToGoogle'])->name('tenant.auth.google.redirect');
Route::get('auth/google/callback', [TenantAuthController::class, 'handleGoogleCallback'])->name('tenant.auth.google.callback');

// Central admin auth routes
Route::get('admin/login', [CentralAdminAuthController::class, 'showLoginForm'])->name('central.admin.login');
Route::post('admin/login', [CentralAdminAuthController::class, 'login']);
Route::post('admin/logout', [CentralAdminAuthController::class, 'logout'])->name('central.admin.logout');

// Redirect root to central admin login
Route::get('/', function () {
    return redirect()->route('central.admin.login');
});
