<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantDatabaseService;

class TenantDashboardController extends Controller
{
    protected $tenantDatabaseService;

    public function __construct(TenantDatabaseService $tenantDatabaseService)
    {
        $this->tenantDatabaseService = $tenantDatabaseService;
    }

    public function dashboard()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Handle guest user if not authenticated
        if (!$user || $user->id === 0) {
            $user = (object)[
                'id' => 0,
                'name' => 'Guest User',
                'email' => 'guest@example.com',
                'role' => 'guest',
            ];
        }

        // Fetch products or any other data you need for the POS dashboard
        // Fix: TenantDatabaseService does not have getProducts(), fetch products directly from tenant connection
        $products = \App\Models\Product::on('tenant')->get();

        return view('tenant.pos.dashboard', compact('user', 'products'));
    }

    public function tenantDashboard()
    {
        $tenant = request()->attributes->get('tenant');
        $products = \App\Models\Product::on('tenant')->get();
        return view('tenant.dashboard', compact('tenant', 'products'));
    }

    public function editProfile()
    {
        // Placeholder method for tenant.profile.edit route
        return view('tenant.profile.edit'); // Assuming this view exists or create a simple view
    }
}
