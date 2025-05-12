<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Make sure to import the Hash facade
use App\Services\TenantDatabaseService;
use App\Models\Tenant;
use App\Models\GuestUser;
use Illuminate\Support\Facades\DB;

class TenantAuthController extends Controller
{
    protected $tenantDatabaseService;

    public function __construct(TenantDatabaseService $tenantDatabaseService)
    {
        $this->tenantDatabaseService = $tenantDatabaseService;
    }


     public function showLoginForm()
{
    // Fetch all registered tenants
   $tenants = Tenant::all(); // Make sure to import the Tenant model at the top

  return view('tenant.auth.login', [
        'tenants' => $tenants,
    ]);
}

    protected function loginAsGuest()
    {
        $guestUser = new GuestUser();
        // Hash the password before saving
        $guestUser->password = Hash::make('password');
        $guestUser->save();
        Auth::guard('tenant')->login($guestUser);
        return redirect()->route('tenant.pos.dashboard')->with('message', 'Logged in as guest.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $tenantName = strtolower(str_replace(' ', '_', $request->input('tenant')));

        $this->tenantDatabaseService->setTenantConnection($tenantName);

        try {
            DB::connection('tenant')->getPdo(); // Test connection
        } catch (\Exception $e) {
            return back()->withErrors(['tenant' => 'Could not connect to the tenant database.']);
        }

        if (Auth::guard('tenant')->attempt(
            $request->only('email', 'password'), 
            $request->filled('remember')
        )) {
            $request->session()->regenerate();
            // Preserve tenant parameter in redirect
            $tenantParam = $request->input('tenant');
            return redirect()->route('tenant.pos.dashboard', ['tenant' => $tenantParam]);
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }
}

