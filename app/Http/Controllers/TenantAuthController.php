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
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $tenantName = strtolower(str_replace(' ', '_', $request->input('tenant')));

        \Log::info("Attempting to set tenant connection for tenant: {$tenantName}");
        $this->tenantDatabaseService->setTenantConnection($tenantName);

        try {
            DB::connection('tenant')->getPdo();
            \Log::info("Successfully connected to tenant database: {$tenantName}");
        } catch (\Exception $e) {
            \Log::error("Failed to connect to tenant database: {$tenantName}. Error: " . $e->getMessage());
            return back()->withErrors(['tenant' => 'Could not connect to the tenant database.']);
        }

        // Pass a success message about tenant database connection to the view
        $connectionMessage = "Database '{$tenantName}' connected successfully.";

        // Debug: Retrieve user by email from tenant connection
        $email = strtolower($request->input('email'));
        $user = \App\Models\TenantUser::on('tenant')->where('email', $email)->first();

        if (!$user) {
            \Log::warning("Tenant login failed: User not found with email {$email} in tenant {$tenantName}");
            return back()->withErrors(['email' => 'Email not found.'])->withInput($request->only('email', 'tenant'))->with('connectionMessage', $connectionMessage);
        } else {
            \Log::info("Tenant login attempt for user {$user->email} in tenant {$tenantName}");
            // Check password manually
            if (!\Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
                \Log::warning("Tenant login failed: Password mismatch for user {$user->email} in tenant {$tenantName}");
                return back()->withErrors(['password' => 'Incorrect password.'])->withInput($request->only('email', 'tenant'))->with('connectionMessage', $connectionMessage);
            } else {
                \Log::info("Tenant login password verified for user {$user->email} in tenant {$tenantName}");
            }
        }

        // Prioritize manual credential check before Auth attempt
        $user = \App\Models\TenantUser::on('tenant')->where('email', $email)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {
            Auth::guard('tenant')->login($user, $request->filled('remember'));
            $request->session()->regenerate();
            $tenantParam = $request->input('tenant');
            return redirect()->route('tenant.pos.dashboard', ['tenant' => $tenantParam]);
        }

        // If authentication fails, return back with error message and tenant query parameter
        \Log::warning("Tenant login failed: Invalid credentials for email {$request->input('email')} in tenant {$tenantName}");
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput($request->only('email', 'tenant'))->with('connectionMessage', $connectionMessage);
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }
}

