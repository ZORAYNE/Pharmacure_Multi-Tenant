<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\TenantDatabaseService;
use App\Models\GuestUser;
use Illuminate\Support\Facades\DB;

class AdminTenantController extends Controller
{
   public function store(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'tenant_name' => 'required|string|regex:/^[A-Za-z0-9 _-]+$/|unique:tenants,tenant_name',
           'full_name' => 'required|string|max:255',
           'email' => 'required|email|unique:tenants,email',
           'password' => 'required|string|min:6|confirmed',
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

       return redirect()->route('admin.dashboard')->with('success', 'Tenant registered successfully. Please accept the tenant to create a database.');
   }

   public function edit($tenantName)
   {
       $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();
       return view('admin.tenants.edit', compact('tenant'));
   }

   public function update(Request $request, $tenantName)
   {
       $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();

       $validator = Validator::make($request->all(), [
           'full_name' => 'required|string|max:255',
           'email' => 'required|email|unique:tenants,email,' . $tenant->id,
           'password' => 'nullable|string|min:6|confirmed',
       ]);

       if ($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
       }

       $tenant->full_name = $request->full_name;
       $tenant->email = $request->email;

       if ($request->filled('password')) {
           $tenant->password = Hash::make($request->password);
       }

       $tenant->save();

       return redirect()->route('admin.dashboard')->with('success', 'Tenant updated successfully.');
   }

   public function accept($tenantName, Request $request)
   {
       $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();
       $tenant->status = 'accepted';
       $tenant->save();

       // Inject TenantDatabaseService
       $tenantDatabaseService = app(TenantDatabaseService::class);

       try {
           // 1) Create the tenant database
           $tenantDatabaseService->createDatabase($tenant->tenant_name);

           // 2) Set tenant connection
           $tenantDatabaseService->setTenantConnection($tenant->tenant_name);

           // 3) Run migrations on the tenant database
           $tenantDatabaseService->runMigrations($tenant->tenant_name);

           // 4) Create user in the tenant database
           $this->createUserInTenantDatabase($tenant);

           // 5) Send acceptance email to tenant
           // Pass plaintext password to mail class
           \Mail::to($tenant->email)->send(new \App\Mail\TenantAcceptedMail($tenant, $request->input('password')));

           return redirect()->back()
               ->with('success', 'Tenant accepted and database created.')
               ->with('tenantAccepted', true)
               ->with('plaintext_password', $request->input('password'));
       } catch (\Exception $e) {
           return redirect()->back()->withErrors(['database' => 'Failed to create or migrate tenant database: ' . $e->getMessage()]);
       }
   }

   protected function createUserInTenantDatabase($tenant)
   {
       DB::connection('tenant')->table('users')->insert([
           'name' => $tenant->full_name,
           'email' => $tenant->email,
           'password' => Hash::make($tenant->password),
           'created_at' => now(),
           'updated_at' => now(),
       ]);
   }

   public function delete($tenantName)
   {
       $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();
       $tenantDatabaseName = 'tenant_' . $tenant->tenant_name;
       $tenant->delete(); 

       if ($tenantDatabaseName) {
           $this->dropTenantDatabase($tenantDatabaseName);
       }

       return redirect()->back()->with('success', 'Tenant deleted.');
   }

   protected function dropTenantDatabase($databaseName)
   {
       $connection = \DB::connection('mysql');
       $connection->statement("DROP DATABASE IF EXISTS {$databaseName}");
   }

   public function revert($tenantName)
   {
       $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();
       $tenant->status = 'pending';
       $tenant->save();
       return redirect()->back()->with('success', 'Tenant status reverted to pending.');
   }
}
