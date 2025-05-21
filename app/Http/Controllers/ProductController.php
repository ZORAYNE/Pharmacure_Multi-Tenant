<?php

namespace App\Http\Controllers;

use App\Models\Product; // Ensure you have a Product model created
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all(); // Fetch all products

        return view('products.index', compact('products')); // Return the view with products
    }

    /**
     * Store a new product in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Removed authorization check as per user request

        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'expiration_date' => 'nullable|date',
        ]);

        $tenant = Auth::user(); // Assuming tenant is authenticated user

        $subscriptionPlan = $tenant->subscription_plan ?? 'basic';

        $productCount = Product::count();

        if ($subscriptionPlan === 'basic' && $productCount >= 5) {
            return redirect()->back()->withErrors(['limit' => 'Basic plan allows up to 5 products only.']);
        } elseif ($subscriptionPlan === 'advance' && $productCount >= 10) {
            return redirect()->back()->withErrors(['limit' => 'Advance plan allows up to 10 products only.']);
        }
        // Pro plan has no limit

        Product::create($request->only(['name', 'brand', 'price', 'stock_quantity', 'expiration_date'])); // Create new product

        $tenantId = request()->route('tenant') ?? Auth::user()->id ?? null;
        return redirect()->route('tenant.pos.dashboard', ['tenant' => $tenantId])->with('success', 'Product added successfully.'); // Redirect with success message
    }

    /**
     * Update the specified product in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('manage-products');

        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'expiration_date' => 'nullable|date',
        ]);

        $product->update($request->only(['name', 'brand', 'price', 'stock_quantity', 'expiration_date']));

        return redirect()->route('tenant.pos.dashboard', ['tenant' => Auth::user()->id])->with('success', 'Product updated successfully.');
    }
}
