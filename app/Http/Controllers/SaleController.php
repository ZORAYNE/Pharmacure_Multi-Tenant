<?php

namespace App\Http\Controllers;

use App\Models\Product; // Ensure you have a Product model created
use App\Models\Sale; // Ensure you have a Sale model created
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Show the form for creating a sale.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $products = Product::all(); // Fetch all products available for sale

        return view('sales.create', compact('products')); // Return sale creation view
    }

    /**
     * Store a newly created sale in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Fetch product to calculate total price and update stock
        $product = Product::find($request->product_id);
        
        if ($request->quantity > $product->stock_quantity) {
            return redirect()->back()->withErrors(['quantity' => 'Not enough stock available.']);
        }

        // Create the sale
        Sale::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity,
        ]);

        // Update product stock
        $product->stock_quantity -= $request->quantity;
        $product->save();

        return redirect()->route('sales.index')->with('success', 'Sale completed successfully.'); // Redirect with success message
    }
}
