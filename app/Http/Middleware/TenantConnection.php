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
    $tenantName = $request->route('tenant') ?? $request->query('tenant') ?? session('tenant');

    if (!$tenantName) {
        abort(400, 'Tenant identifier missing.');
    }

    $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();

    $conn = config('database.connections.mysql');
    $conn['database'] = $this->getDatabaseName($tenant->tenant_name); // Use tenant_name as the database name
    Config::set('database.connections.tenant', $conn);
    DB::purge('tenant');

    $request->attributes->set('tenant', $tenant);

    return $next($request);
}
}