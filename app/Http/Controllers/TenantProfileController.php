<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TenantProfileController extends Controller
{
    public function edit($tenantName)
    {
        $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();
        return view('tenant.profile.edit', compact('tenant'));
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

        return redirect()->route('tenant.dashboard.view', ['tenant' => $tenantName])->with('success', 'Profile updated successfully.');
    }
}
