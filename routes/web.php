<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AdminTenantController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TenantAuthController;
use App\Http\Controllers\CentralAdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUpdaterController;
use App\Http\Controllers\ProductController;


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
    // tenant routes
    Route::get('dashboard', [TenantDashboardController::class, 'dashboard'])->name('tenant.dashboard');
    Route::get('/tenant/pos/dashboard', [TenantDashboardController::class, 'dashboard'])->name('tenant.pos.dashboard');
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'tenantDashboard'])->name('tenant.dashboard.view');
    Route::get('pos', [SaleController::class, 'index'])->name('pos.index');
    Route::get('/tenant/users/create', [TenantDashboardController::class, 'usersCreate'])->name('tenant.users.create');
    Route::get('/tenant/profile/edit', [TenantDashboardController::class, 'editProfile'])->name('tenant.profile.edit');
    Route::post('/tenant/profile/update', [TenantProfileController::class, 'update'])->name('tenant.profile.update');
    Route::get('tenant/pos/index', [SaleController::class, 'index'])->name('tenant.pos.index');
    Route::get('tenant/pos/page', [SaleController::class, 'posPage'])->name('tenant.pos.page');
    Route::post('users', [TenantDashboardController::class, 'store'])->name('tenant.users.store');
    
    // New route for subscription plan update
    Route::post('tenant/update-subscription-plan', [TenantDashboardController::class, 'updateSubscriptionPlan'])->name('tenant.updateSubscriptionPlan');
  
    // Sales routes  
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('sales/{sale}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.invoice');

    // Product routes
    Route::get('products/create', [ProductController::class, 'create'])->name('tenant.products.create');
    Route::post('products', [ProductController::class, 'store'])->name('tenant.products.store');
    Route::match(['put', 'patch'], 'products/{product}', [ProductController::class, 'update'])->name('tenant.products.update');

    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('tenant.products.destroy');

    // Report routes
    Route::get('reports', [ReportController::class, 'showReportForm'])->name('reports.index');
    Route::post('reports/generate', [ReportController::class, 'generateReport'])->name('reports.generate');
    Route::get('reports/sales', [SaleController::class, 'salesReport'])->name('sales.report');
    Route::get('reports/products', [SaleController::class, 'productReport'])->name('products.report');
    
     // Pharmacist routes
    Route::get('pharmacists', [PharmacistController::class, 'index'])->name('pharmacists.index');
    Route::get('pharmacists/create', [PharmacistController::class, 'create'])->name('pharmacists.create');
    Route::post('pharmacists', [PharmacistController::class, 'store'])->name('pharmacists.store');


});

 Route::group(['prefix' => '{tenant}', 'middleware' => ['auth', 'tenant']], function () {
    Route::resource('pharmacists', PharmacistController::class)->except(['show', 'create']);
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

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::prefix('{tenant}')->group(function () {
    Route::post('/reports/send-email', [ReportController::class, 'sendEmail'])->name('reports.sendEmail');
});



