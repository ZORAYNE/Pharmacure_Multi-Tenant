<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreTenantInSession
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('tenant')) {
            $request->session()->put('tenant', $request->query('tenant'));
        }
        return $next($request);
    }
}
