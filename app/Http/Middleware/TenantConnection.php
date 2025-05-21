<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantConnection
{
    public function handle(Request $request, Closure $next)
{
    $tenantName = $request->route('tenant') ?? $request->query('tenant') ?? $request->session()->get('tenant');

    if (!$tenantName) {
        abort(400, 'Tenant identifier missing.');
    }

    // Normalize tenantName: lowercase and replace spaces with underscores
    $tenantNameNormalized = strtolower(str_replace(' ', '_', $tenantName));

    $tenant = Tenant::where('tenant_name', $tenantNameNormalized)->firstOrFail();

    $conn = config('database.connections.mysql');
    $conn['database'] = $tenant->tenant_name; // Use tenant_name as the database name
    Config::set('database.connections.tenant', $conn);
    DB::purge('tenant');

    // Set default connection to tenant
    Config::set('database.default', 'tenant');
    DB::reconnect('tenant');

    $request->attributes->set('tenant', $tenant);

    return $next($request);
}
}
