<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
        {
            // Fetch all tenants
        $tenants = Tenant::all(); // Fetch all tenants from the database

        // You might want to count users or any other logic here
        $userCount = 0; // Initialize or fetch user count as needed

        return view('admin.dashboard', compact('tenants', 'userCount'));
        }
}