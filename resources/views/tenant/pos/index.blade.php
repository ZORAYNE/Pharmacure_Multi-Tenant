<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pharmacy POS</title>
    <meta charset="UTF-8" />
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-theme {
            background-color: #121212;
            color: #e0e0e0;
        }
        /* Header */
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
            user-select: none;
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-theme header {
            background-color: #b86b1e;
            color: #f0f0f0;
        }
        header .left-header {
            cursor: pointer;
        }
        header .center-header {
            display: flex;
            flex-direction: column;
            text-align: center;
            font-weight: bold;
            font-size: 1em;
            gap: 2px;
        }
        header .right-header {
            cursor: pointer;
            position: relative;
            user-select: none;
        }
        header .right-header:hover .dropdown {
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
            min-width: 180px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
            z-index: 1000;
            transition: background-color 0.3s, color 0.3s;
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
            transition: background-color 0.3s, color 0.3s;
        }
        header .dropdown a:hover, header .dropdown form button:hover {
            background-color: #f0f0f0;
        }

        /* Layout container */
        main {
            display: flex;
            height: calc(100vh - 60px);
            padding: 10px;
            gap: 10px;
            box-sizing: border-box;
        }

        /* Sidebar */
        .sidebar {
            background-color: #e67e22;
            color: white;
            width: 250px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            padding: 10px;
            box-sizing: border-box;
            user-select: none;
        }
        .sidebar-header {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
            border-bottom: 2px solid white;
            padding-bottom: 5px;
            border-radius: 6px 6px 0 0;
        }
        .selected-items {
            flex: 1;
            background: white;
            color: black;
            overflow-y: auto;
            padding: 10px;
            border-radius: 0 0 0 0;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .selected-item {
            display: flex;
            align-items: center;
            margin: 5px 0;
            background: rgba(230, 126, 34, 0.1);
            padding: 5px;
            border-radius: 6px;
            color: black;
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
            background-color: #d35400;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-radius: 0 0 6px 6px;
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

        /* Main content */
        .main-content {
            flex: 1;
            background: white;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .content-header {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #e67e22;
            padding: 5px 10px;
            border-radius: 10px 10px 0 0;
            color: white;
            font-weight: bold;
            user-select: none;
        }
        #subscriptionPlanDisplay {
            font-weight: bold;
            white-space: nowrap;
        }
        #saleHistoryBtn, #reportsBtn {
            border: none;
            border-radius: 4px;
            padding: 5px 15px;
            font-weight: bold;
            cursor: pointer;
            color: white;
            user-select: none;
        }
        #saleHistoryBtn {
            background-color: #f39c12;
        }
        #reportsBtn {
            background-color: #28a745;
        }
        #searchBar {
            margin-left: auto;
            padding: 5px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 150px;
        }
        form#saleForm {
            flex: 1;
            overflow-y: auto;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 0 0 10px 10px;
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
    </style>
</head>
<body>
<header>
    <div class="left-header" onclick="window.location='{{ route('tenant.pos.dashboard', ['tenant' => request()->route('tenant')]) }}'">
        PHARMACURE - POS
    </div>
    <div class="center-header">
        <span>{{ $tenantName ?? '{TENANT_NAME}' }}</span>
        <span>{{ $role ?? '{ROLE}' }}</span>
    </div>
    <div class="right-header">
        SETTINGS
        <div class="dropdown">
            <a href="#" id="openProfileModalBtn">Profile</a>
            <a href="{{ route('admin.updater') }}" id="openUpdaterModalBtnDropdown" role="button" aria-haspopup="true" aria-controls="updaterModal" aria-expanded="false">Laravel Updater</a>
            <a href="#" id="openSubscriptionModalBtn" style="cursor: pointer;">Subscription</a>
        <button id="themeToggleBtn" type="button" style="width: 100%; background: none; border: none; padding: 10px 15px; text-align: left; cursor: pointer; color: black;">
            Toggle Light/Dark Theme
        </button>
        <script>
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const body = document.body;

            // Load saved theme from localStorage
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark-theme');
                themeToggleBtn.style.color = 'white';
            }

            themeToggleBtn.addEventListener('click', () => {
                body.classList.toggle('dark-theme');
                if (body.classList.contains('dark-theme')) {
                    themeToggleBtn.style.color = 'white';
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeToggleBtn.style.color = 'black';
                    localStorage.setItem('theme', 'light');
                }
            });
        </script>
<form method="POST" action="{{ route('tenant.logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</header>

<!-- Sale History Modal -->
<div id="saleHistoryModal" class="modal" role="dialog" aria-modal="true" tabindex="0" style="display:none; justify-content: center; align-items: center;">
    <div class="modal-content" tabindex="0" style="pointer-events: auto; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; background: white; border-radius: 10px; padding: 20px; position: relative;">
        <button id="closeSaleHistoryModal" class="close" tabindex="0" aria-label="Close sale history modal" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</button>
        <h2>Sale History</h2>
        <div id="saleHistoryContent">
            @if($sales && $sales->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px;">Sale ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Date and Time / Report Type</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $sale->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $sale->created_at->format('Y-m-d H:i:s') }} / Sale
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <a href="{{ url('sales/invoice/' . $sale->id) }}" target="_blank" rel="noopener noreferrer">Generate PDF</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>No sale history available.</p>
            @endif
        </div>
    </div>
</div>

<!-- Reports Modal -->
<div id="reportsModal" class="modal" role="dialog" aria-modal="true" tabindex="0" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div class="modal-content" tabindex="0" style="pointer-events: auto; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; background: white; border-radius: 10px; padding: 20px; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <span id="closeReportsModal" class="close" tabindex="0" role="button" aria-label="Close reports modal" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
        <h2>Reports</h2>
        <div id="reportsContent">
<form id="reportForm" method="POST" action="{{ route('reports.generate', ['tenant' => request()->route('tenant')]) }}">
                @csrf
                <div style="margin-bottom: 10px;">
                    <label for="reportType">Report Type:</label>
                    <select id="reportType" name="report_type" required>
                        <option value="" disabled selected>Select report type</option>
                        <option value="total sales">Total Sales</option>
                        <option value="stock left">Stock Left</option>
                        <option value="expired products">Expired Products</option>
                        <option value="most sold">Most Sold</option>
                        <option value="least sold">Least Sold</option>
                        <option value="invoice">Invoice</option>
                    </select>
                </div>
                <div style="margin-bottom: 10px;">
                    <label for="period">Period:</label>
                    <select id="period" name="period">
                        <option value="" selected>All Time</option>
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                </div>
                <div id="invoiceSelection" style="display:none; margin-bottom: 10px;">
                    <label>Invoices:</label>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllInvoices" /></th>
                                <th>Sale ID</th>
                                <th>Date and Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr>
                                <td><input type="checkbox" name="sale_id[]" value="{{ $sale->id }}" class="invoiceCheckbox" /></td>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 10px;">
                    <button type="submit" id="generateReportBtn" name="action" value="generate" style="margin-right: 10px;">Generate Report</button>
                    <button type="submit" id="sendEmailBtn" name="action" value="email">Send to Email</button>
                </div>
            </form>
                <script>
                    const reportForm = document.getElementById('reportForm');
                    const generateReportBtn = document.getElementById('generateReportBtn');
                    const sendEmailBtn = document.getElementById('sendEmailBtn');

                    generateReportBtn.addEventListener('click', () => {
                        reportForm.action = "{{ route('reports.generate', ['tenant' => request()->route('tenant')]) }}";
                        reportForm.target = "_blank"; // open PDF in new tab
                    });

                    sendEmailBtn.addEventListener('click', () => {
                        reportForm.action = "{{ route('reports.sendEmail', ['tenant' => request()->route('tenant')]) }}";
                        reportForm.target = "_self"; // submit normally
                    });
                </script>
        </div>
    </div>
</div>

<main>
     <div class="sidebar">
        <div class="sidebar-header">ITEMS:</div>
        <div class="selected-items" id="selectedItems">
            <!-- Selected products will be listed here -->
        </div>
        <div class="sidebar-footer">
            <button type="submit" form="saleForm" id="submitBtn">CHECKOUT</button>
            <span>TOTAL PRICE: <span id="totalPrice">0.00</span></span>
        </div>
      </div>  
    <div class="main-content">
            <div class="content-header">
                <div id="subscriptionPlanDisplay">Subscription Plan: {{ $subscriptionPlan ?? 'advance' }}</div>
                <button id="saleHistoryBtn">Sale History</button>
                <button id="reportsBtn">Reports</button>
                <input type="text" id="searchBar" placeholder="SEARCHBAR" />
            </div>
        <form method="POST" action="{{ route('sales.store', ['tenant' => $tenantRouteParam]) }}" id="saleForm">
            @csrf
            <table>
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
                        <td style="width: 80px;"><img src="{{ $product->picture ?? asset('images/no-picture-available.png') }}" alt="Product Image" /></td>
                        <td style="width: 150px; word-wrap: break-word;">{{ $product->name }}</td>
                        <td style="width: 100px; word-wrap: break-word;">{{ $product->brand }}</td>
                        <td style="width: 80px;">₱{{ number_format($product->price, 2) }}</td>
                        <td style="width: 80px;">
                            <input type="number" min="1" max="{{ $product->stock_quantity }}" value="1" class="quantityInput" data-product-id="{{ $product->id }}" style="width: 60px;" />
                        </td>
                        <td style="width: 100px;">
                            <button type="button" class="action-btn selectBtn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-brand="{{ $product->brand }}" data-product-price="{{ $product->price }}" data-product-picture="{{ $product->picture ?? asset('images/no-picture-available.png') }}">Select</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</main>
</body>
                    
                    </tbody>
                </table>
            </div>
        </div>
</main>

<!-- Payment Modal -->
<div id="paymentModal" class="modal" role="dialog" aria-modal="true" tabindex="0" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div class="modal-content" tabindex="0" style="pointer-events: auto; max-width: 400px; width: 90%; background: white; border-radius: 10px; padding: 20px; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <span id="closePaymentModalBtn" class="close" tabindex="0" role="button" aria-label="Close payment modal" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>      <h2>Payment</h2>
        <label for="cashInput">Cash:</label>
        <input type="number" id="cashInput" min="0" step="0.01" autofocus />
        <label for="changeOutput">Change:</label>
        <input type="number" id="changeOutput" readonly />
        <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
            <button id="cancelPaymentBtn" type="button">Cancel</button>
            <button id="confirmPaymentBtn" type="button" disabled>Confirm Payment</button>
        </div>
    </div>
</div>

<!-- Subscription Modal -->
<div id="subscriptionModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="subscriptionModalTitle" aria-modal="true" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; width: 90%; max-width: 400px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative;">
        <span id="closeSubscriptionModalBtn" class="close" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
        <h2 id="subscriptionModalTitle">Subscription Plans</h2>
            <form id="subscriptionForm">
                <ul style="list-style: none; padding-left: 0;">
                    <li>
                        <label>
                            <input type="radio" name="subscriptionPlan" value="basic" />
                            <strong>Basic:</strong> Up to 5 products, 1 pharmacist, access to POS only.
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="subscriptionPlan" value="advance" />
                            <strong>Advance:</strong> Up to 10 products, 3 pharmacists, access to POS, reports, and sales history.
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="subscriptionPlan" value="pro" />
                            <strong>Pro:</strong> Unlimited products and pharmacists, full access including subscription management and advanced reports.
                        </label>
                    </li>
                </ul>
                <button type="submit" style="margin-top: 10px; padding: 8px 12px; background-color: #e67e22; color: white; border: none; border-radius: 6px; cursor: pointer;">Select Plan</button>
            </form>
            <div id="subscriptionMessage" style="margin-top: 10px; font-weight: bold;"></div>
            <script>
                document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const selectedPlan = document.querySelector('input[name="subscriptionPlan"]:checked');
                    if (!selectedPlan) {
                        alert('Please select a subscription plan.');
                        return;
                    }
                    const planValue = selectedPlan.value;
                    fetch('{{ url(request()->route('tenant') . "/tenant/update-subscription-plan") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ subscription_plan: planValue })
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('subscriptionMessage').textContent = data.message;
                        // Update the displayed subscription plan dynamically
                        if (window.updateSubscriptionPlanDisplay) {
                            window.updateSubscriptionPlanDisplay(data.subscription_plan);
                        }
                    })
                    .catch(error => {
                        document.getElementById('subscriptionMessage').textContent = 'Error updating subscription plan.';
                    });
                });

                // Define the function to update the displayed subscription plan dynamically
                window.updateSubscriptionPlanDisplay = function(plan) {
                    const displayDiv = document.getElementById('subscriptionPlanDisplay');
                    if (displayDiv) {
                        displayDiv.textContent = 'Subscription Plan: ' + plan;
                    }
                };
            </script>
</div>
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
 <!-- Added missing updater message modal for Laravel Updater button -->
        <div id="updaterMessageModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="updaterMessageModalTitle" aria-modal="true" style="display:none; position: fixed; z-index: 3100; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
            <div class="modal-content" style="background: white; padding: 20px; border-radius: 10px; width: 90%; max-width: 400px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative;">
                <span id="closeUpdaterMessageModalBtn" class="close" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
                <h2 id="updaterMessageModalTitle">Update Status</h2>
                <div id="updaterMessageContent" style="margin-top: 10px; font-weight: bold;"></div>
            </div>
        </div>
<script>
    //profile edit modal
    const profileModal = document.getElementById('profileModal');
    const openProfileModalBtn = document.getElementById('openProfileModalBtn');
    const closeProfileModalBtn = document.getElementById('closeProfileModalBtn');

    if (openProfileModalBtn) {
        openProfileModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (profileModal) {
                profileModal.style.display = 'flex';
            }
        });
    }

    if (closeProfileModalBtn) {
        closeProfileModalBtn.addEventListener('click', () => {
            if (profileModal) {
                profileModal.style.display = 'none';
            }
        });
    }

    window.addEventListener('click', (event) => {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
    });
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
                <div>Price: ₱${product.price.toFixed(2)}</div>
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
        const existingInputs = saleForm.querySelectorAll('input[name^="products"]');
        existingInputs.forEach(input => input.remove());

        selectedProducts.forEach((product, index) => {
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `products[${index}][product_id]`;
            inputId.value = product.id;
            saleForm.appendChild(inputId);

            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `products[${index}][quantity]`;
            inputQty.value = product.quantity;
            saleForm.appendChild(inputQty);
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

    // Payment modal functionality
    const paymentModal = document.getElementById('paymentModal');
    const cashInput = document.getElementById('cashInput');
    const changeOutput = document.getElementById('changeOutput');
    const cancelPaymentBtn = document.getElementById('cancelPaymentBtn');
    const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');

    // Show payment modal on checkout button click
    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (selectedProducts.length === 0) {
            alert('Please select at least one product before checkout.');
            return;
        }
        cashInput.value = '';
        changeOutput.value = '';
        confirmPaymentBtn.disabled = true;
        paymentModal.style.display = 'flex';
        cashInput.focus();
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
        const serializedData = new URLSearchParams(formData).toString();
        console.log('Serialized form data:', serializedData);

        fetch(saleForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: serializedData,
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
            const invoiceUrl = `{{ url(request()->route('tenant') . '/sales') }}/${data.sale_id}/invoice`;
            window.open(invoiceUrl, '_blank');
        })
        .catch(errorData => {
            alert('Error processing sale: ' + (errorData.message || 'Unknown error'));
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
    // Sale History and Reports modal open/close functionality
    const saleHistoryBtn = document.getElementById('saleHistoryBtn');
    const reportsBtn = document.getElementById('reportsBtn');
    const saleHistoryModal = document.getElementById('saleHistoryModal');
    const reportsModal = document.getElementById('reportsModal');
    const closeSaleHistoryModalBtn = document.getElementById('closeSaleHistoryModal');
    const closeReportsModalBtn = document.getElementById('closeReportsModal');

    if (saleHistoryBtn && saleHistoryModal && closeSaleHistoryModalBtn) {
        saleHistoryBtn.addEventListener('click', () => {
            saleHistoryModal.style.display = 'flex';
        });
        closeSaleHistoryModalBtn.addEventListener('click', () => {
            saleHistoryModal.style.display = 'none';
        });
    }

    if (reportsBtn && reportsModal && closeReportsModalBtn) {
        reportsBtn.addEventListener('click', () => {
            reportsModal.style.display = 'flex';
        });
        closeReportsModalBtn.addEventListener('click', () => {
            reportsModal.style.display = 'none';
        });
    }

    // Show/hide invoice selection table based on report type
    const reportTypeSelect = document.getElementById('reportType');
    const invoiceSelectionDiv = document.getElementById('invoiceSelection');
    const selectAllInvoicesCheckbox = document.getElementById('selectAllInvoices');
    const invoiceCheckboxes = document.querySelectorAll('.invoiceCheckbox');

    function toggleInvoiceSelection() {
        if (reportTypeSelect.value === 'invoice') {
            invoiceSelectionDiv.style.display = 'block';
        } else {
            invoiceSelectionDiv.style.display = 'none';
            // Uncheck all invoice checkboxes when hiding
            invoiceCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllInvoicesCheckbox) {
                selectAllInvoicesCheckbox.checked = false;
            }
        }
    }

    if (reportTypeSelect) {
        reportTypeSelect.addEventListener('change', toggleInvoiceSelection);
        // Initialize on page load
        toggleInvoiceSelection();
    }

    // Handle select all invoices checkbox
    if (selectAllInvoicesCheckbox) {
        selectAllInvoicesCheckbox.addEventListener('change', function() {
            invoiceCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // Close modals when clicking outside modal content
    window.addEventListener('click', (event) => {
        if (event.target === saleHistoryModal) {
            saleHistoryModal.style.display = 'none';
        }
        if (event.target === reportsModal) {
            reportsModal.style.display = 'none';
        }
    });
</script>
</body>
</html>