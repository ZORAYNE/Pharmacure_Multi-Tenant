<!DOCTYPE html>
<html>
<head>
    <title>Add Product - PHARMACURE</title>
    <meta charset="utf-8" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 600px; }
        label { display: block; margin-top: 1rem; }
        input, button { width: 100%; padding: 0.5rem; margin-top: 0.25rem; }
        button { margin-top: 1rem; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; }
        .error { color: red; margin-top: 0.5rem; }
    </style>
</head>
<body>

<h1>Add New Product</h1>

@if($errors->any())
    <div class="error">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    @csrf

    <label for="name">Product Name:</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required />

    <label for="brand">Brand:</label>
    <input id="brand" type="text" name="brand" value="{{ old('brand') }}" required />

    <label for="stock_quantity">Stock Quantity:</label>
    <input id="stock_quantity" type="number" name="stock_quantity" min="0" value="{{ old('stock_quantity') }}" required />

    <label for="expiration_date">Expiration Date:</label>
    <input id="expiration_date" type="date" name="expiration_date" value="{{ old('expiration_date') }}" />

    <label for="picture">Product Picture (optional):</label>
    <input id="picture" type="file" name="picture" accept="image/*" />

    <button type="submit">Add Product</button>
</form>

</body>
</html>
