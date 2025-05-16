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
        /* Navbar styles */
        .navbar {
            background-color: #007bff;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
        }
        .navbar .nav-left, .navbar .nav-right {
            display: flex;
            align-items: center;
        }
        .navbar .nav-left a, .navbar .nav-right a, .navbar .nav-right button {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .navbar .nav-left a:hover, .navbar .nav-right a:hover, .navbar .nav-right button:hover {
            text-decoration: underline;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            border-radius: 4px;
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            font-weight: normal;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        form.logout-form {
            display: inline;
        }
        form.logout-form button {
            background: none;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            margin: 0;
            padding: 0;
        }
        form.logout-form button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <a href="{{ route('tenant.users.create', ['tenant' => request()->route('tenant')]) }}">Add User</a>
    </div>
    <div class="nav-right">
        <div class="dropdown">
            <a href="javascript:void(0)">Settings &#x25BC;</a>
            <div class="dropdown-content">
        <a href="{{ route('tenant.profile.edit', ['tenant' => request()->route('tenant')]) }}">Profile</a>
                <a href="{{ route('admin.updater') }}">Laravel Updater</a>
            </div>
        </div>
        <form method="POST" action="{{ route('tenant.logout') }}" class="logout-form">
            @csrf
            <button type="submit">Log Out</button>
        </form>
    </div>
</div>

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