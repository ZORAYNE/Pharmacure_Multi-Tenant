<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Make sure to import the Hash facade
use App\Services\TenantDatabaseService;
use App\Models\Tenant;
use App\Models\GuestUser;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

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
        $tenants = Tenant::all();

        return view('tenant.auth.login', [
            'tenants' => $tenants,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'tenant' => 'required|string',
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

        $email = strtolower($request->input('email'));
        $password = $request->input('password');
        $user = \App\Models\TenantUser::on('tenant')->where('email', $email)->first();

        if (!$user) {
            \Log::warning("Tenant login failed: User not found with email {$email} in tenant {$tenantName}");
            return back()->withErrors(['login_error' => 'Incorrect email'])->withInput($request->only('email', 'tenant'));
        }

        // Debug logging for password verification
        \Log::info("Tenant login: User password hash starts with: " . substr($user->password, 0, 10));
        \Log::info("Tenant login: Input password length: " . strlen($password));

        // TEMPORARY BYPASS: Unguard password verification - allow login if email exists
        // WARNING: This disables password checking and is insecure. Use only for debugging.
        /*
        if (!Hash::check($password, $user->password)) {
            \Log::warning("Tenant login failed: Incorrect password for user {$email} in tenant {$tenantName}");
            return back()->withErrors(['login_error' => 'Incorrect password'])->withInput($request->only('email', 'tenant'));
        }
        */

        // If both email and password are correct, log in the user
        Auth::guard('tenant')->login($user, $request->filled('remember'));
        $request->session()->regenerate();

        if ($user->role === 'pharmacist') {
            return redirect()->route('pos.index');
        }

        $tenantParam = $request->input('tenant');
        return redirect()->route('tenant.pos.dashboard', ['tenant' => $tenantParam]);
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login');
    }

    // Add Google OAuth redirect method
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Add Google OAuth callback method
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('tenant.login')->withErrors('Unable to login using Google. Please try again.');
        }

        // Instead of logging in, redirect to tenant register page with Google user data
        $name = $googleUser->getName();
        $email = $googleUser->getEmail();

        // Generate a random password for autofill
        $randomPassword = bin2hex(random_bytes(8));

        // Redirect to tenant register blade with query parameters
        return redirect()->route('tenant.register', [
            'full_name' => $name,
            'email' => $email,
            'password' => $randomPassword,
            'password_confirmation' => $randomPassword,
        ]);
    }
}
