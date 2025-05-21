<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
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
        $products = Product::on('tenant')->get();

        return view('sales.create', compact('products'));
    }

    /**
     * Display the POS dashboard page with products and totals.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::on('tenant')->get();

        $totalProducts = $products->count();

        $totalSales = Sale::on('tenant')->sum('total_price');

        return view('tenant.pos.dashboard', compact('products', 'totalProducts', 'totalSales'));
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
            'products' => 'required|array',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $productsData = $request->input('products');

        // Custom validation for product_id existence on tenant connection
        foreach ($productsData as $index => $item) {
            $productId = $item['product_id'] ?? null;
            if (!$productId || !Product::on('tenant')->where('id', $productId)->exists()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "The selected product ID {$productId} is invalid.",
                        'errors' => ["products.{$index}.product_id" => "The selected product ID {$productId} is invalid."]
                    ], 422);
                }
                return redirect()->back()->withErrors([
                    "products.{$index}.product_id" => "The selected product ID {$productId} is invalid."
                ]);
            }
        }

        // Debug: Log received product IDs
        \Log::info('Received product IDs:', array_map(fn($p) => $p['product_id'], $productsData));

        $sale = Sale::create([
            // Set product_id to first product's id to satisfy DB constraint
            'product_id' => $productsData[0]['product_id'] ?? 1,
            // Add quantity_sold if needed, sum of quantities
            'quantity_sold' => array_sum(array_column($productsData, 'quantity')),
            'total_price' => 0, // Initialize total_price to avoid SQL error
        ]);

        $totalSalePrice = 0;

        foreach ($productsData as $item) {
            // Use tenant connection explicitly
            $product = Product::on('tenant')->find($item['product_id']);
            if (!$product) {
                \Log::error("Product ID {$item['product_id']} not found in tenant database.");
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "Invalid product ID {$item['product_id']}.",
                        'errors' => ['product_id' => "Invalid product ID {$item['product_id']}."]
                    ], 422);
                }
                return redirect()->back()->withErrors(['product_id' => "Invalid product ID {$item['product_id']}."]);
            }

            $quantity = $item['quantity'];

            if ($quantity > $product->stock_quantity) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "Not enough stock for product {$product->name}.",
                        'errors' => ['quantity' => "Not enough stock for product {$product->name}."]
                    ], 422);
                }
                return redirect()->back()->withErrors(['quantity' => "Not enough stock for product {$product->name}."]);
            }

            $price = $product->price;
            $totalPrice = $price * $quantity;

            $sale->saleItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $product->stock_quantity -= $quantity;
            $product->save();

            $totalSalePrice += $totalPrice;
        }

        $sale->total_price = $totalSalePrice;
        $sale->save();

        // TODO: Generate invoice PDF here and trigger download or save file

        $tenant = request()->route('tenant');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Sale completed successfully.',
                'sale_id' => $sale->id,
            ]);
        }

        return redirect()->route('sales.invoice', ['tenant' => $tenant, 'sale' => $sale->id])->with('success', 'Sale completed successfully.');
    }

    /**
     * Generate invoice PDF for a sale.
     *
     * @param int $saleId
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice($tenant, $saleId)
    {
        $sale = Sale::on('tenant')->with('saleItems.product')->findOrFail($saleId);

        $reportGenerator = app()->make(\App\Services\ReportGenerator::class);

        $dateTime = now()->format('Ymd_His');
        $saleCount = Sale::on('tenant')->count();

        $fileName = "{$dateTime}_T-{$saleCount}.pdf";
        $pdfContent = $reportGenerator->generateInvoice($sale->id);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
    }

    /**
     * Display the POS page with product selection and sale form.
     *
     * @return \Illuminate\View\View
     */
    public function posPage()
    {
        $products = Product::on('tenant')->get();
        $sales = Sale::on('tenant')->with('saleItems.product')->orderBy('created_at', 'desc')->get();

        $totalProducts = $products->count();
        $totalSales = Sale::on('tenant')->sum('total_price');

        // Get tenant info from request or session
        $tenant = request()->attributes->get('tenant');
        $tenantName = $tenant ? $tenant->tenant_name : 'Tenant Name';

        // Get authenticated user info (tenant admin or pharmacist)
        $user = auth()->user();
        $fullName = $user ? ($user->full_name ?? $user->name) : 'Tenant Admin';
        $role = $user ? ($user->role ?? 'Role') : 'Role';

        // Pass tenant route parameter explicitly
        $tenantRouteParam = request()->route('tenant');

        return view('tenant.pos.index', compact('products', 'sales', 'totalProducts', 'totalSales', 'tenantName', 'fullName', 'role', 'tenantRouteParam'));
    }
}
