<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\TenantDatabaseService;

class TenantRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('tenant.register');
    }

    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'tenant_name' => 'required|string|regex:/^[A-Za-z0-9 _-]+$/|unique:tenants,tenant_name',
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tenants,email',
            'password' => 'sometimes|required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Sanitize tenant name
        $tenantName = str_replace(' ', '_', $request->tenant_name);
        $tenantName = strtolower($tenantName);

        // Create the tenant record in the central database
        $tenant = Tenant::create([
            'tenant_name' => $tenantName,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        // Inject TenantDatabaseService
        $tenantDatabaseService = app(TenantDatabaseService::class);

        try {
            // 1) Create the tenant's database
            $tenantDatabaseService->createDatabase($tenantName);

            // 2) Set tenant connection
            $tenantDatabaseService->setTenantConnection($tenantName);

            // 3) Run migrations on tenant database
            $tenantDatabaseService->runMigrations($tenantName);

            // 4) Insert tenant admin user into tenant database
            \DB::connection('tenant')->table('users')->insert([
                'name' => $tenant->full_name,
                'email' => $tenant->email,
                'role' => 'admin',
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('tenant.login')->with('success', 'Tenant registered successfully. You can now log in.');
        } catch (\Exception $e) {
            $tenant->delete(); 
            return redirect()->back()->withErrors([
                'database' => 'Failed to create or migrate tenant database: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
