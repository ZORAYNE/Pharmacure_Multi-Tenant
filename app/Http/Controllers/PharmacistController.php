<?php

namespace App\Http\Controllers;

use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacistController extends Controller
{
    /**
     * Display a listing of pharmacists.
     */
    public function index()
    {
        $pharmacists = TenantUser::where('role', 'pharmacist')->get();
        return view('tenant.pharmacists.index', compact('pharmacists'));
    }

    /**
     * Show the form for creating a new pharmacist.
     */
    public function create()
    {
        return view('tenant.pharmacists.create');
    }

    /**
     * Store a newly created pharmacist in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'certification_number' => 'required|string|max:255',
        ]);

        $tenant = Auth::user();
        $subscriptionPlan = $tenant->subscription_plan ?? 'basic';

        $pharmacistCount = TenantUser::where('role', 'pharmacist')->count();

        if ($subscriptionPlan === 'basic' && $pharmacistCount >= 1) {
            return redirect()->back()->withErrors(['limit' => 'Basic plan allows only 1 pharmacist.']);
        } elseif ($subscriptionPlan === 'advance' && $pharmacistCount >= 3) {
            return redirect()->back()->withErrors(['limit' => 'Advance plan allows up to 3 pharmacists.']);
        }
        // Pro plan has no limit

        TenantUser::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'role' => 'pharmacist',
            'password' => bcrypt($request->password),
            'certification_number' => $request->certification_number,
        ]);

        return redirect()->route('pharmacists.index')->with('success', 'Pharmacist added successfully.');
    }
}
