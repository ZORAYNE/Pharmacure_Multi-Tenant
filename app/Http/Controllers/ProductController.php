<?php

namespace App\Http\Controllers;

use App\Models\Product; // Ensure you have a Product model created
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        Product::create($request->all()); // Create new product

        return redirect()->route('products.index')->with('success', 'Product added successfully.'); // Redirect with success message
    }
}
