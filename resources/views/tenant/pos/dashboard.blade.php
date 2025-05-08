<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pharmacy POS Dashboard</title>
    <meta charset="UTF-8" />
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 800px; margin: auto; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .guest-message { color: red; margin-bottom: 1rem; }
        button { padding: 0.5rem 1rem; margin-top: 1rem; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Pharmacy POS Dashboard</h1>

        <p>Hello, {{ $user->name }} ({{ $user->role }})</p>

        @if ($user->role === 'guest')
            <p class="guest-message">You are logged in as a guest. Some features may be limited.</p>
        @endif

        <h2>Available Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No products available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Logout Button -->
        <form action="{{ route('tenant.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
