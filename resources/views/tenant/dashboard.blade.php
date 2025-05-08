<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Store Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button { margin: 0.5rem; }
        .success { color: green; }
        .error { color: red; }
        a.button-link {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 4px 4px 0;
            background-color: #007bff;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        a.button-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Welcome to {{ $tenant->full_name }}'s Pharmacy Store Dashboard</h1>

<h2>Available Products</h2>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>${{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <form method="POST" action="{{ route('products.addToCart', $product->id) }}">
                        @csrf
                        <button type="submit">Add to Cart</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>