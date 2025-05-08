<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\TenantDatabaseService;
use App\Models\GuestUser;

class AdminTenantController extends Controller
{
    //public function create()
    //{
    //    return view('admin.tenants.create');
   // }

   public function accept($id)
{
    $tenant = Tenant::findOrFail($id);
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

        return redirect()->back()->with('success', 'Tenant accepted and database created.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['database' => 'Failed to create or migrate tenant database: ' . $e->getMessage()]);
    }
}


 
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

    // Create the tenant record in the central database, without creating a DB
    $tenant = Tenant::create([
        'tenant_name' => $tenantName,
        'full_name' => $request->full_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'status' => 'pending',
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Tenant registered successfully. Please accept the tenant to create a database.');
}


    protected function loginAsGuest()
    {
        // Create a guest user instance
        $guestUser = new GuestUser();
        // Hash the password before saving
        $guestUser->password = Hash::make('password'); // You can define your own logic for guest password
        $guestUser->save();
        Auth::guard('tenant')->login($guestUser); // Log in as guest
        return redirect()->route('tenant.dashboard')->with('message', 'Logged in as guest.');
    }

    public function delete($id)
    {
        // Check if the tenant exists first
        $tenant = Tenant::findOrFail($id);
    
        // Logic for handling deletion.
        // You can also add verification logic to ensure the database exists before deletion
        $tenantDatabaseName = 'tenant_' . $tenant->tenant_name;
    
        // Delete the tenant record
        $tenant->delete(); 
    
        // Clean up tenant DB if it exists
        if ($tenantDatabaseName) {
            $this->dropTenantDatabase($tenantDatabaseName);
        }
    
        return redirect()->back()->with('success', 'Tenant deleted.');
    }
    

// Method to drop the tenant's database
protected function dropTenantDatabase($databaseName)
{
    // Assuming you're using the same connection to execute the query
    $connection = \DB::connection('your_connection_name'); // Modify as needed

    // Drop the database
    $connection->statement("DROP DATABASE IF EXISTS {$databaseName}");
}
  public function revert($id)
   {
       $tenant = Tenant::findOrFail($id);
       $tenant->status = 'pending';
       $tenant->save();
       return redirect()->back()->with('success', 'Tenant status reverted to pending.');
   }

}
