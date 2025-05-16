<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy POS</title>
    <meta charset="utf-8" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 600px; }
        label { display: block; margin-top: 1rem; }
        input, select, button { width: 100%; padding: 0.5rem; margin-top: 0.25rem; }
        button { margin-top: 1rem; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; }
        .error { color: red; margin-top: 0.5rem; }
    </style>
</head>
<body>

<h1>PHARMACURE - POS</h1>

<div class="hidden sm:flex sm:items-center sm:ms-6">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800">
                <div class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <x-responsive-nav-link :href="route('superadmin.profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('superadmin.logout') }}">
                @csrf
                <x-dropdown-link :href="route('superadmin.logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>

@if($errors->any())
    <div class="error">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('sales.store') }}">
    @csrf

    <label for="product_id">Select Product:</label>
    <select id="product_id" name="product_id" required>
        <option value="" disabled selected>Select product</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock_quantity }}">
                {{ $product->name }} - ${{ number_format($product->price, 2) }} (Stock: {{ $product->stock_quantity }})
            </option>
        @endforeach
    </select>

    <label for="quantity">Quantity:</label>
    <input id="quantity" type="number" name="quantity" min="1" value="1" required />

    <p><strong>Total Price: $<span id="totalPrice">0.00</span></strong></p>

    <button type="submit">Complete Sale & Generate Invoice</button>
</form>

<script>
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const totalPriceEl = document.getElementById('totalPrice');

    function updateTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
        const stock = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
        let quantity = parseInt(quantityInput.value);
        if (isNaN(quantity) || quantity < 1) quantity = 1;

        if (quantity > stock) {
            quantity = stock;
            quantityInput.value = stock;
        }

        const total = price * quantity;
        totalPriceEl.textContent = total.toFixed(2);
    }

    productSelect.addEventListener('change', updateTotal);
    quantityInput.addEventListener('input', updateTotal);

    // Initialize total price on load
    window.addEventListener('load', updateTotal);
</script>

</body>
</html>
