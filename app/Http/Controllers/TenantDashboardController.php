<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantDatabaseService;
use App\Models\TenantUser;

class TenantDashboardController extends Controller
{
    protected $tenantDatabaseService;

    public function __construct(TenantDatabaseService $tenantDatabaseService)
    {
        $this->tenantDatabaseService = $tenantDatabaseService;
    }

public function dashboard(Request $request)
{
    $user = \Illuminate\Support\Facades\Auth::user();

    // Fetch products or any other data you need for the POS dashboard
    // Fix: TenantDatabaseService does not have getProducts(), fetch products directly from tenant connection
    $products = \App\Models\Product::on('tenant')->paginate(10);

    $totalProducts = $products->total();
    $totalSales = \App\Models\Sale::on('tenant')->sum('total_price');

    // Fetch current and latest Laravel version info from cache or config
    $currentVersion = config('app.version', 'Unknown');
    $latestVersion = \Illuminate\Support\Facades\Cache::get('latest_laravel_version', $currentVersion);

    // Get tenant from route parameter and normalize
    $tenantName = $request->route('tenant');
    $tenantNameNormalized = strtolower(str_replace(' ', '_', $tenantName));
    $tenant = \App\Models\Tenant::where('tenant_name', $tenantNameNormalized)->first();

    // Get authenticated user role if available
    $role = $user->role ?? null;

    // Fetch pharmacists for the tenant scoped by tenant id or tenant name
    // Since TenantUser model does not have tenant_id, fetch all pharmacists (tenant scoped by connection)
    $pharmacists = \App\Models\TenantUser::on('tenant')->where('role', 'pharmacist')->get();

    // Pass subscription plan to view
    $subscriptionPlan = $tenant->subscription_plan ?? 'basic';

    // Pass tenant to view to fix undefined variable error
    return view('tenant.pos.dashboard', compact('user', 'products', 'totalProducts', 'totalSales', 'currentVersion', 'latestVersion', 'tenant', 'tenantName', 'role', 'pharmacists', 'subscriptionPlan'));
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

    // New method to update subscription plan
public function updateSubscriptionPlan(Request $request)
{
    try {
        $request->validate([
            'subscription_plan' => 'required|string|in:basic,advance,pro',
        ]);

        $tenantName = $request->route('tenant');
        $tenantNameNormalized = strtolower(str_replace(' ', '_', $tenantName));
        $tenant = \App\Models\Tenant::where('tenant_name', $tenantNameNormalized)->first();

        if (!$tenant) {
            throw new \Exception('Tenant not found.');
        }

        $tenant->subscription_plan = $request->input('subscription_plan');
        $tenant->save();

        return response()->json(['message' => 'Subscription plan updated successfully.', 'subscription_plan' => $tenant->subscription_plan]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error updating subscription plan: ' . $e->getMessage()], 500);
    }
}
public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|in:pharmacist',
    ]);

    $tenant = Auth::user();
    $subscriptionPlan = $tenant->subscription_plan ?? 'basic';

    $userCount = TenantUser::where('role', 'pharmacist')->count();

    if ($subscriptionPlan === 'basic' && $userCount >= 1) {
        return redirect()->back()->withErrors(['limit' => 'Basic plan allows only 1 pharmacist.']);
    } elseif ($subscriptionPlan === 'advance' && $userCount >= 3) {
        return redirect()->back()->withErrors(['limit' => 'Advance plan allows up to 3 pharmacists.']);
    }
    // Pro plan has no limit

    TenantUser::create([
        'name' => $request->full_name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => bcrypt($request->password),
    ]);

    return redirect()->route('tenant.users.index')->with('success', 'User added successfully.');
}

public function usersCreate(Request $request)
{
    $tenantName = $request->route('tenant');
    return view('tenant.users.create', compact('tenantName'));
}

}
