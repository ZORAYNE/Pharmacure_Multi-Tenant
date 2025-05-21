<!DOCTYPE html>
<html>
<head>
    <title>PHARMACURE - POS</title>
    <meta charset="utf-8" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #e67e22;
            color: black;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 1.2em;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        header .tenant-info {
            display: flex;
            flex-direction: column;
        }
        header .settings {
            position: relative;
            cursor: pointer;
            user-select: none;
        }
        header .settings:hover .dropdown {
            display: block;
        }
        header .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 90px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        header .dropdown a, header .dropdown form button {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: black;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }
        header .dropdown a:hover, header .dropdown form button:hover {
            background-color: #f0f0f0;
        }
        main {
            flex: 1;
            display: flex;
            padding: 10px;
            gap: 10px;
            background: #fff;
        }
        .sidebar {
            width: 300px;
            border-radius: 10px;
            background: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            box-sizing: border-box;
        }
        .sidebar-header {
            background-color: #e67e22;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            user-select: none;
        }
        .selected-items {
            flex: 1;
            background: white;
            overflow-y: auto;
            padding: 10px;
        }
        .selected-item {
            display: flex;
            align-items: center;
            margin: 5px 0;
            background: rgba(230, 126, 34, 0.1);
            padding: 5px;
            border-radius: 6px;
        }
        .selected-item img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: 10px;
            border-radius: 4px;
            background: white;
        }
        .selected-item-details {
            flex: 1;
        }
        .selected-item-details div {
            font-size: 14px;
        }
        .selected-item-quantity {
            margin-left: 10px;
            width: 50px;
        }
        .remove-btn {
            background: #c0392b;
            border: none;
            color: white;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 10px;
        }
        .sidebar-footer {
            background-color: #e67e22;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            user-select: none;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .sidebar-footer button {
            background: transparent;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1em;
            cursor: pointer;
            padding: 0;
            text-align: left;
        }
        .sidebar-footer span {
            font-size: 0.9em;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            background: white;
            padding: 10px;
            box-sizing: border-box;
            height: 100vh;
            overflow-y: auto;
        }
        .content-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            background-color: #e67e22;
            padding: 5px;
            border-radius: 6px;
        }
        .searchbar {
            padding: 5px;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            width: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
        }
        th {
            background: #f39c12;
            font-weight: bold;
        }
        td img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 4px;
            background: white;
        }
        .action-btn {
            background: #27ae60;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .action-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }
        /* Modal overlay */
        #updaterModal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Modal content */
        #updaterModal .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            max-width: 90vw;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            position: relative;
        }
        /* Close button */
        #updaterModal .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header style="display: flex; justify-content: space-between; align-items: center;">
    <div style="font-weight: bold; cursor: pointer;" onclick="window.location='{{ route('tenant.pos.dashboard', ['tenant' => request()->route('tenant')]) }}'">
        PHARMACURE - POS
    </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div>
                <span>{{ $tenantName ?? 'Tenant Name' }}</span>
            </div>
            <div>
                <span>{{ $role ?? 'Role' }}</span>
            </div>
            <div class="settings" style="cursor: pointer; user-select: none;">
                SETTINGS
                <div class="dropdown">
                    <a href="{{ route('tenant.profile.edit', ['tenant' => request()->route('tenant')]) }}">Profile</a>
                    <a href="#" id="openUpdaterModalBtnDropdown" role="button" aria-haspopup="true" aria-controls="updaterModal" aria-expanded="false">Laravel Updater</a>
                    <a href="#" id="openSubscriptionModalBtn" style="cursor: pointer;">Subscription</a>

                    <!-- Subscription Modal -->
                    <div id="subscriptionModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="subscriptionModalTitle" aria-modal="true" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
                        <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; width: 400px; max-width: 90vw; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative;">
                            <span id="closeSubscriptionModalBtn" class="close" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
                            <h2 id="subscriptionModalTitle">Subscription Plans</h2>
                            <ul style="list-style: none; padding-left: 0;">
                                <li><strong>Basic:</strong> Up to 5 products, 1 pharmacist, access to POS only.</li>
                                <li><strong>Advance:</strong> Up to 10 products, 3 pharmacists, access to POS, reports, and sales history.</li>
                                <li><strong>Pro:</strong> Unlimited products and pharmacists, full access including subscription management and advanced reports.</li>
                            </ul>
                        </div>
                    </div>

 
                    <form method="POST" action="{{ route('tenant.logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
</header>
<main style="display: flex; gap: 10px;">
    <div class="sidebar" style="width: 300px; border-radius: 10px; background: white; display: flex; flex-direction: column; height: 100vh; box-sizing: border-box;">
        <div class="sidebar-header" style="background-color: #e67e22; color: white; padding: 10px; font-weight: bold; border-top-left-radius: 10px; border-top-right-radius: 10px; user-select: none;">
            ITEMS:
        </div>
        <div class="selected-items" id="selectedItems" style="flex: 1; background: white; overflow-y: auto; padding: 10px;">
            <!-- Selected products will be listed here -->
        </div>
        <div class="sidebar-footer" style="background-color: #e67e22; color: white; padding: 10px; font-weight: bold; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; user-select: none; display: flex; flex-direction: column; gap: 5px;">
            <button type="submit" form="saleForm" id="submitBtn" style="background: transparent; border: none; color: white; font-weight: bold; font-size: 1em; cursor: pointer; padding: 0; text-align: left;">
                CHECKOUT
            </button>
            <span>TOTAL PRICE: <span id="totalPrice">0.00</span></span>
        </div>
    </div>

    <div class="container" style="flex: 1; display: flex; flex-direction: column; border-radius: 10px; background: white; padding: 10px; box-sizing: border-box; height: 100vh; overflow-y: auto;">
        <div class="content-header" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; background-color: #e67e22; padding: 5px; border-radius: 6px;">
            @php
                $subscriptionPlan = auth()->user()->subscription_plan ?? 'basic';
            @endphp
            @if($subscriptionPlan !== 'basic')
                <button id="saleHistoryBtn" style="padding: 5px 10px; background-color: #f39c12; border: none; border-radius: 4px; color: white; cursor: pointer; font-weight: bold;">Sale History</button>
                <button id="reportsBtn" style="padding: 5px 10px; background-color: #28a745; border: none; border-radius: 4px; color: white; cursor: pointer; font-weight: bold;">Reports</button>
            @endif
            <input type="text" id="searchBar" class="searchbar" placeholder="SEARCHBAR" style="padding: 5px; font-size: 14px; border-radius: 4px; border: none; width: 200px;" />
        </div>

        <!-- Reports Modal -->
        <div id="reportsModal" class="modal" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
            <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; width: 600px; max-width: 90vw; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative;">
                <span id="closeReportsModal" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
                @include('tenant.reports.index')
            </div>
        </div>

        <!-- Sale History Modal -->
        <div id="saleHistoryModal" class="modal" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
            <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; width: 600px; max-width: 90vw; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative;">
                <span id="closeSaleHistoryModal" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
                <h2>Sale History</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">Date & Time</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2;">File</th>
                        </tr>
                    </thead>
                    <tbody id="saleHistoryTableBody" style="border: 1px solid #ddd;">
                        <!-- Sale history rows will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
        <form method="POST" action="{{ route('sales.store', ['tenant' => request()->route('tenant')]) }}" id="saleForm" style="flex: 1; display: flex; flex-direction: column; overflow: auto;">
            @csrf
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr>
                        <th>PRODUCT IMAGE</th>
                        <th>NAME</th>
                        <th>BRAND</th>
                        <th>PRICE</th>
                        <th>QUANTITY</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @foreach($products as $product)
                    <tr data-name="{{ strtolower($product->name) }}">
                        <td><img src="{{ $product->picture ?? asset('images/no-picture-available.png') }}" alt="Product Image" /></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <input type="number" min="1" max="{{ $product->stock_quantity }}" value="1" class="quantityInput" data-product-id="{{ $product->id }}" />
                        </td>
                        <td>
                            <button type="button" class="action-btn selectBtn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-brand="{{ $product->brand }}" data-product-price="{{ $product->price }}" data-product-picture="{{ $product->picture ?? asset('images/no-picture-available.png') }}">Select</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</main>

<!-- Updater Modal -->
<div id="updaterModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeUpdaterModalBtn">&times;</span>
        <h2>Laravel Updater</h2>
        <div class="info">
            <p>Current Version: <span id="currentVersion">{{ $currentVersion ?? 'Unknown' }}</span></p>
            <p>Latest Version: <span id="latestVersion">{{ $latestVersion ?? 'Unknown' }}</span></p>
        </div>
        <button id="checkUpdateBtn">Check for Updates</button>
        <button id="performUpdateBtn" disabled>Perform Update</button>
        <div id="updaterMessage"></div>
    </div>
</div>

<script>
    // Modal functionality for Laravel Updater
    const updaterModal = document.getElementById('updaterModal');
    const openUpdaterModalBtnDropdown = document.getElementById('openUpdaterModalBtnDropdown');
    const closeUpdaterModalBtn = document.getElementById('closeUpdaterModalBtn');
    const checkUpdateBtn = document.getElementById('checkUpdateBtn');
    const performUpdateBtn = document.getElementById('performUpdateBtn');
    const updaterMessage = document.getElementById('updaterMessage');
    const latestVersionSpan = document.getElementById('latestVersion');

    if (openUpdaterModalBtnDropdown) {
        openUpdaterModalBtnDropdown.addEventListener('click', () => {
            updaterModal.style.display = 'block';
        });
    }

    if (closeUpdaterModalBtn) {
        closeUpdaterModalBtn.addEventListener('click', () => {
            updaterModal.style.display = 'none';
        });
    }

    window.addEventListener('click', (event) => {
        if (event.target == updaterModal) {
            updaterModal.style.display = 'none';
        }
    });

    checkUpdateBtn.addEventListener('click', () => {
        updaterMessage.textContent = 'Checking for updates...';
        fetch('{{ route("admin.updater.check") }}')
            .then(response => response.json())
            .then(data => {
                if (data.updateAvailable) {
                    updaterMessage.textContent = 'Update available: ' + data.latestVersion;
                    latestVersionSpan.textContent = data.latestVersion;
                    performUpdateBtn.disabled = false;
                } else {
                    updaterMessage.textContent = 'No updates available.';
                    performUpdateBtn.disabled = true;
                }
            })
            .catch(() => {
                updaterMessage.textContent = 'Error checking for updates.';
                performUpdateBtn.disabled = true;
            });
    });

    performUpdateBtn.addEventListener('click', () => {
        updaterMessage.textContent = 'Performing update...';
        performUpdateBtn.disabled = true;
        fetch('{{ route("admin.updater.perform") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updaterMessage.textContent = data.message;
                    checkUpdateBtn.click();
                } else {
                    updaterMessage.textContent = 'Update failed: ' + data.message;
                    performUpdateBtn.disabled = false;
                }
            })
            .catch(() => {
                updaterMessage.textContent = 'Error performing update.';
                performUpdateBtn.disabled = false;
            });
    });

    // Selected products management
    const selectedItemsContainer = document.getElementById('selectedItems');
    const totalPriceSpan = document.getElementById('totalPrice');
    const submitBtn = document.getElementById('submitBtn');
    const saleForm = document.getElementById('saleForm');

    let selectedProducts = [];

    function renderSelectedItems() {
        selectedItemsContainer.innerHTML = '';
        let totalPrice = 0;

        selectedProducts.forEach(product => {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('selected-item');

            const img = document.createElement('img');
            img.src = product.picture;
            img.alt = product.name;

            const detailsDiv = document.createElement('div');
            detailsDiv.classList.add('selected-item-details');
            detailsDiv.innerHTML = `
                <div><strong>${product.name}</strong></div>
                <div>Price: $${product.price.toFixed(2)}</div>
                <div>Quantity: ${product.quantity}</div>
            `;

            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.min = 1;
            quantityInput.value = product.quantity;
            quantityInput.classList.add('selected-item-quantity');
            quantityInput.addEventListener('change', (e) => {
                const newQty = parseInt(e.target.value);
                if (isNaN(newQty) || newQty < 1) {
                    e.target.value = product.quantity;
                    return;
                }
                product.quantity = newQty;
                renderSelectedItems();
            });

            const removeBtn = document.createElement('button');
            removeBtn.classList.add('remove-btn');
            removeBtn.textContent = 'Remove';
            removeBtn.addEventListener('click', () => {
                selectedProducts = selectedProducts.filter(p => p.id !== product.id);
                renderSelectedItems();
            });

            itemDiv.appendChild(img);
            itemDiv.appendChild(detailsDiv);
            itemDiv.appendChild(quantityInput);
            itemDiv.appendChild(removeBtn);

            selectedItemsContainer.appendChild(itemDiv);

            totalPrice += product.price * product.quantity;
        });

        totalPriceSpan.textContent = totalPrice.toFixed(2);
        submitBtn.style.display = selectedProducts.length > 0 ? 'inline-block' : 'none';

        // Update hidden inputs in the form for submission
        const existingInputs = saleForm.querySelectorAll('input[name="products[]"]');
        existingInputs.forEach(input => input.remove());

        selectedProducts.forEach(product => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'products[]';
            input.value = JSON.stringify({
                product_id: product.id,
                quantity: product.quantity
            });
            saleForm.appendChild(input);
        });
    }

    // Handle select button clicks in product table
    document.querySelectorAll('.selectBtn').forEach(button => {
        button.addEventListener('click', () => {
            const productId = parseInt(button.getAttribute('data-product-id'));
            const productName = button.getAttribute('data-product-name');
            const productBrand = button.getAttribute('data-product-brand');
            const productPrice = parseFloat(button.getAttribute('data-product-price'));
            const productPicture = button.getAttribute('data-product-picture');

            const quantityInput = button.closest('tr').querySelector('.quantityInput');
            let quantity = parseInt(quantityInput.value);
            if (isNaN(quantity) || quantity < 1) quantity = 1;

            // Check if product already selected
            const existingProduct = selectedProducts.find(p => p.id === productId);
            if (existingProduct) {
                existingProduct.quantity += quantity;
            } else {
                selectedProducts.push({
                    id: productId,
                    name: productName,
                    brand: productBrand,
                    price: productPrice,
                    picture: productPicture,
                    quantity: quantity
                });
            }
            renderSelectedItems();
        });
    });

    // Search filter for product table
    const searchBar = document.getElementById('searchBar');
    searchBar.addEventListener('input', () => {
        const filter = searchBar.value.toLowerCase();
        const rows = document.querySelectorAll('#productTableBody tr');
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Subscription modal functionality
    const subscriptionModal = document.getElementById('subscriptionModal');
    const openSubscriptionModalBtn = document.getElementById('openSubscriptionModalBtn');
    const closeSubscriptionModalBtn = document.getElementById('closeSubscriptionModalBtn');

    if (openSubscriptionModalBtn) {
        openSubscriptionModalBtn.onclick = function() {
            if (subscriptionModal) {
                subscriptionModal.style.display = "flex";
                subscriptionModal.setAttribute('aria-hidden', 'false');
            }
        }
    }

    if (closeSubscriptionModalBtn) {
        closeSubscriptionModalBtn.onclick = function() {
            if (subscriptionModal) {
                subscriptionModal.style.display = "none";
                subscriptionModal.setAttribute('aria-hidden', 'true');
            }
        }
    }

    window.onclick = function(event) {
        if (event.target == subscriptionModal) {
            subscriptionModal.style.display = "none";
            subscriptionModal.setAttribute('aria-hidden', 'true');
        }
    }

    // Reports modal 
    const reportsBtn = document.getElementById('reportsBtn');
    const reportsModal = document.getElementById('reportsModal');
    const closeReportsModal = document.getElementById('closeReportsModal');

    reportsBtn.addEventListener('click', () => {
        reportsModal.style.display = 'flex';
    });

    closeReportsModal.addEventListener('click', () => {
        reportsModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === reportsModal) {
            reportsModal.style.display = 'none';
        }
    });    
    
    document.addEventListener('DOMContentLoaded', function () {
        const paymentModal = document.getElementById('paymentModal');
        const cashInput = document.getElementById('cashInput');
        const changeOutput = document.getElementById('changeOutput');
        const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');
        const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
        const checkoutBtn = document.getElementById('submitBtn');
        const saleForm = document.getElementById('saleForm');
        const totalPriceSpan = document.getElementById('totalPrice');

        // Show payment modal on checkout button click
        checkoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            cashInput.value = '';
            changeOutput.value = '';
            confirmPaymentBtn.disabled = true;
            paymentModal.style.display = 'flex';
        });

        // Close payment modal
        cancelPaymentBtn.addEventListener('click', function () {
            paymentModal.style.display = 'none';
        });
        document.getElementById('closePaymentModalBtn').addEventListener('click', function () {
            paymentModal.style.display = 'none';
        });

        // Calculate change and enable confirm button
        cashInput.addEventListener('input', function () {
            const cash = parseFloat(cashInput.value);
            const total = parseFloat(totalPriceSpan.textContent);
            if (!isNaN(cash) && cash >= total) {
                changeOutput.value = (cash - total).toFixed(2);
                confirmPaymentBtn.disabled = false;
            } else {
                changeOutput.value = '';
                confirmPaymentBtn.disabled = true;
            }
        });

        // Confirm payment and submit sale form via AJAX
        confirmPaymentBtn.addEventListener('click', function () {
            const formData = new FormData(saleForm);

            fetch(saleForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => { throw data; });
                }
                return response.json();
            })
            .then(data => {
                paymentModal.style.display = 'none';
                window.location.reload(); // simple reload to reset UI

                // Open invoice PDF in new tab
                const invoiceUrl = `{{ url('sales/invoice') }}/${data.sale_id}`;
                window.open(invoiceUrl, '_blank');
            })
            .catch(errorData => {
                alert('Error processing sale: ' + (errorData.message || 'Unknown error'));
            });
        });
    });
</script>

<!-- Payment Modal -->
<div id="paymentModal" class="modal" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; width: 400px; max-width: 90vw; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative;">
        <span id="closePaymentModalBtn" class="close" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
        <h2>Payment</h2>
        <label for="cashInput">Cash:</label>
        <input type="number" id="cashInput" min="0" step="0.01" />
        <label for="changeOutput">Change:</label>
        <input type="number" id="changeOutput" readonly />
        <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
            <button id="cancelPaymentBtn" type="button">Cancel</button>
            <button id="confirmPaymentBtn" type="button" disabled>Confirm Payment</button>
        </div>
    </div>
</div>
</body>
</html>
