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
        $user = Auth::guard('tenant')->user();

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
        $products = $this->tenantDatabaseService->getProducts();

        return view('tenant.pos.dashboard', compact('user', 'products'));
    }
}
