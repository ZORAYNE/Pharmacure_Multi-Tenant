<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $currentVersion = config('app.version', 'unknown');
        $latestVersion = null; // Optionally, fetch latest version here or leave null

        $tenants = Tenant::all();

        return view('admin.dashboard', compact('tenants', 'currentVersion', 'latestVersion'));
    }
}
