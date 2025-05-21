<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pharmacy POS Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="subscription-plan" content="{{ $subscriptionPlan ?? 'basic' }}">
    <link rel="stylesheet" href="{{ asset('build/css/pos-dashboard.css') }}">
</head>
<body>
    <nav class="navbar">
        <div>
            <h1 style="font-weight: bold; color: white; margin: 0;">Welcome to the Pharmacy POS Dashboard</h1>
            <div style="color: white;">Tenant Name:{{ $tenantName ?? '{TENANT_NAME}' }}</div>
        </div>
        <div class="dropdown" aria-label="Settings dropdown" id="settingsDropdown">
            <button id="settingsBtn" aria-haspopup="true" aria-expanded="false">Settings &#x25BC;</button>
            <div class="dropdown-content" role="menu" aria-hidden="true" id="dropdownContent">

                <!-- Dark/Light Theme Toggle Button -->
                <button id="themeToggleBtn" type="button" style="width: 100%; background: none; border: none; padding: 0.5rem 1rem; text-align: left; cursor: pointer; color: black;">
                    Toggle Light/Dark Theme
                </button>

                <!-- Add User Button to open modal -->
                <button id="openAddUserModalBtn" type="button" style="width: 100%; background: none; border: none; padding: 0.5rem 1rem; text-align: left; cursor: pointer; color: black;">
                    Add User
                </button>

                <a href="#" id="openProfileModalBtn" style="display: block; padding: 0.5rem 1rem; cursor: pointer; color: black; text-decoration: none;">
                    Profile
                </a>

                    <a href="#" id="openSubscriptionModalBtn" style="display: block; padding: 0.5rem 1rem; cursor: pointer; color: black; text-decoration: none;">
                    Subscription Plan: {{ $tenant->subscription_plan ?? 'advance' }}
                </a>

                @if(auth()->user() && auth()->user()->role === 'admin')
                <!-- User Accessibility Modal -->
                <div id="userAccessibilityModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" id="closeUserAccessibilityModalBtn">&times;</span>
                        <h2>User Accessibility</h2>
                        <p>Access permissions and restrictions for the current user:</p>
                        <ul id="accessibilityList" style="list-style-type: none; padding-left: 0;">
                            <li><label><input type="checkbox" disabled id="permGenerateReport"> Generate Report</label></li>
                            <li><label><input type="checkbox" disabled id="permAddProducts"> Add Products</label></li>
                            <li><label><input type="checkbox" disabled id="permSendGmail"> Send to Gmail</label></li>
                            <li><label><input type="checkbox" disabled id="permAccessPOS"> Access POS</label></li>
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Subscription Modal -->
                <div id="subscriptionModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="subscriptionModalTitle" aria-modal="true" style="display:none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
                    <div class="modal-content" style="background: white; padding: 20px; border-radius: 30px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative; display: flex; gap: 20px; justify-content: space-around; color: black;">
                        <span id="closeSubscriptionModalBtn" class="close" aria-label="Close" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; color: #333; cursor: pointer;">&times;</span>
                        <div id="basicPlan" class="clickable-plan" style="flex: 1; border: 1px solid #1a2a40; border-radius: 20px; padding: 20px; text-align: center; cursor: pointer;">
                            <h3 style="font-weight: bold;">BASIC</h3>
                            <p>Up to 5 products, 1 pharmacist, access to POS only.</p>
                        </div>
                        <div id="advancedPlan" class="clickable-plan" style="flex: 1; border: 1px solid #1a2a40; border-radius: 20px; padding: 20px; text-align: center; cursor: pointer;">
                            <h3 style="font-weight: bold;">ADVANCED</h3>
                            <p>Up to 10 products, 3 pharmacists, access to POS, reports, and sales history.</p>
                        </div>
                        <div id="proPlan" class="clickable-plan" style="flex: 1; border: 1px solid #1a2a40; border-radius: 20px; padding: 20px; text-align: center; cursor: pointer;">
                            <h3 style="font-weight: bold;">PRO</h3>
                            <p>Unlimited products and pharmacists, full access including subscription management and advanced reports.</p>
                        </div>
                    </div>
                </div>
        <script src="{{ asset('build/js/pos-dashboard.js') }}"></script>


                <!-- Central Admin Laravel Updater Link -->
                <a href="{{ route('admin.updater') }}" style="display: block; padding: 0.5rem 1rem; cursor: pointer; color: black; text-decoration: none;">
                    Laravel Updater
                </a>
                

                <!-- Profile Modal -->
                <div id="profileModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" id="closeProfileModalBtn">&times;</span>
                        <h2>Edit Tenant Profile</h2>
                        <form method="POST" action="{{ route('tenant.profile.update', ['tenant' => request()->route('tenant')]) }}">
                            @csrf
                            @method('PATCH')
                            <label for="full_name" style="display: block; font-weight: bold;">Full Name</label>
                            <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $tenant->full_name ?? '') }}" required style="width: 100%; margin-bottom: 10px; padding: 6px;" />
                            <label for="email" style="display: block; font-weight: bold;">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $tenant->email ?? '') }}" required style="width: 100%; margin-bottom: 10px; padding: 6px;" />
                            <label for="password" style="display: block; font-weight: bold;">Password (leave blank to keep current)</label>
                            <input id="password" type="password" name="password" style="width: 100%; margin-bottom: 10px; padding: 6px;" />
                            <label for="password_confirmation" style="display: block; font-weight: bold;">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" style="width: 100%; margin-bottom: 10px; padding: 6px;" />
                            <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer;">Update Profile</button>
                        </form>
                    </div>
                </div>

                <!-- Laravel Updater Modal -->
                <div id="updaterModal" class="modal" style="display:none; justify-content: center; align-items: center;">
                    <div class="modal-content" style="color: black;">
                        <span class="close" id="closeUpdaterModalBtn" role="button" tabindex="0" aria-label="Close updater modal">&times;</span>
                        <h2>Laravel Updater</h2>
                        <div class="info">
                            <p>Current Version: <span id="currentVersion">{{ $currentVersion ?? 'Unknown' }}</span></p>
                            <p>Latest Version: <span id="latestVersion">{{ $latestVersion ?? 'Unknown' }}</span></p>
                        </div>
                        <button id="checkUpdateBtn" type="button">Check for Updates</button>
                        <button id="performUpdateBtn" type="button" disabled>Perform Update</button>
                        <div id="updaterMessage">Latest version: please wait for the update</div>
                    </div>
                </div>

            <form method="POST" action="{{ route('tenant.logout') }}" style="padding: 1rem; margin: 0;">
                @csrf
                <button type="submit">Logout</button>
            </form>

            </div>
        </div>
        
   <!-- Add User Modal -->
<div id="addUserModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="addUserModalTitle" aria-modal="true" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeAddUserModalBtn" aria-label="Close">&times;</span>
        <h2 id="addUserModalTitle">Add New User</h2>
        <form action="{{ route('tenant.users.store', ['tenant' => request()->route('tenant')]) }}" method="POST" style="margin: 0;">
            @csrf
            <input type="text" name="full_name" placeholder="Full Name" required style="width: 100%; margin-bottom: 0.5rem; padding: 0.25rem;" />
            <input type="email" name="email" placeholder="User  Email" required style="width: 100%; margin-bottom: 0.5rem; padding: 0.25rem;" />
            <input type="password" name="password" placeholder="Password" required style="width: 100%; margin-bottom: 0.5rem; padding: 0.25rem;" />
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required style="width: 100%; margin-bottom: 0.5rem; padding: 0.25rem;" />
            <select name="role" style="width: 100%; margin-bottom: 0.5rem; padding: 0.25rem;">
                <option value="pharmacist">Pharmacist</option>
            </select>
            <button type="submit" style="width: 100%; background-color: #28a745; color: white; border: none; padding: 0.5rem; border-radius: 4px;">Add User</button>
        </form>
    </div>
</div>


    </nav>

    <div class="container">
        <div class="totals">
            <div>Total Products: {{ $totalProducts }}</div>
            <div>Total Sales: ₱{{ number_format($totalSales, 2) }}</div>
        </div>
<button id="goToPosBtn" type="button" onclick="window.location.href='{{ url('' . request()->route('tenant') . '/tenant/pos/page') }}'">Go to POS (Point-of-Sale)</button>
        <button id="openModalBtn" type="button">Add Product</button>
        <button type="button" id="openPharmacistsModalBtn" style="margin-left: 10px; padding: 0.5rem 1rem; background-color: #17a2b8; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer;">Pharmacists</button>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Expiration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>{{ $product->expiration_date ? \Carbon\Carbon::parse($product->expiration_date)->format('Y-m-d') : 'N/A' }}</td>
<td>
    <button class="edit-btn" data-product="{{ json_encode($product) }}" type="button">Edit</button>
    <form action="{{ route('tenant.products.destroy', ['tenant' => request()->route('tenant'), 'product' => $product->id]) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="edit-btn" style="background-color: #dc3545; margin-left: 5px;">Delete</button>
    </form>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No products available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="modalTitle" aria-modal="true" style="display:none;">
        <div class="modal-content">
            <span class="close" id="closeAddModalBtn" aria-label="Close">&times;</span>
            <h1 id="modalTitle">Add New Product</h1>

            @if($errors->any())
                <div class="error">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="addProductForm" method="POST" action="{{ route('tenant.products.store', ['tenant' => request()->route('tenant')]) }}" enctype="multipart/form-data">
                @csrf
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td><label for="name">Product Name:</label></td>
                        <td><input id="name" type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 6px;"/></td>
                    </tr>
                    <tr>
                        <td><label for="brand">Brand:</label></td>
                        <td><input id="brand" type="text" name="brand" value="{{ old('brand') }}" required style="width: 100%; padding: 6px;"/></td>
                    </tr>
                    <tr>
                        <td><label for="price">Price:</label></td>
                        <td><input id="price" type="number" step="0.01" name="price" value="{{ old('price') }}" required style="width: 100%; padding: 6px;"/></td>
                    </tr>
                    <tr>
                        <td><label for="stock_quantity">Stock Quantity:</label></td>
                        <td><input id="stock_quantity" type="number" name="stock_quantity" min="0" value="{{ old('stock_quantity') }}" required style="width: 100%; padding: 6px;"/></td>
                    </tr>
                    <tr>
                        <td><label for="expiration_date">Expiration Date:</label></td>
                        <td><input id="expiration_date" type="date" name="expiration_date" value="{{ old('expiration_date') }}" style="width: 100%; padding: 6px;"/></td>
                    </tr>
                    <tr>
                        <td><label for="picture">Product Picture (optional):</label></td>
                        <td><input id="picture" type="file" name="picture" accept="image/*" style="width: 100%; padding: 6px;"/></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-top: 10px;">
                            <button type="submit" class="submit-btn" style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Add Product</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="editModalTitle" aria-modal="true" style="display:none;">
        <div class="modal-content">
            <span class="close" id="closeEditModalBtn" aria-label="Close">&times;</span>
            <h1 id="editModalTitle">Edit Product</h1>

            @if($errors->any())
                <div class="error" style="color: red; margin-bottom: 1rem;">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td><label for="edit_name">Product Name:</label></td>
                        <td><input id="edit_name" type="text" name="name" required style="width: 100%; padding: 6px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="edit_brand">Brand:</label></td>
                        <td><input id="edit_brand" type="text" name="brand" required style="width: 100%; padding: 6px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="edit_price">Price:</label></td>
                        <td><input id="edit_price" type="number" step="0.01" name="price" required style="width: 100%; padding: 6px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="edit_stock_quantity">Stock Quantity:</label></td>
                        <td><input id="edit_stock_quantity" type="number" name="stock_quantity" min="0" required style="width: 100%; padding: 6px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="edit_expiration_date">Expiration Date:</label></td>
                        <td><input id="edit_expiration_date" type="date" name="expiration_date" style="width: 100%; padding: 6px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="edit_picture">Product Picture (optional):</label></td>
                        <td><input id="edit_picture" type="file" name="picture" accept="image/*" style="width: 100%; padding: 6px;" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-top: 10px;">
                            <button type="submit" class="submit-btn" style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Update Product</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <script>
 //Laravel Updater
    const updaterModal = document.getElementById('updaterModal');
    const openUpdaterModalBtn = document.getElementById('openUpdaterModalBtn');
    const closeUpdaterModalBtn = document.getElementById('closeUpdaterModalBtn');
    const checkUpdateBtn = document.getElementById('checkUpdateBtn');
    const performUpdateBtn = document.getElementById('performUpdateBtn');
    const updaterMessage = document.getElementById('updaterMessage');
    const latestVersionSpan = document.getElementById('latestVersion');

    // Add event listener to the Laravel Updater link to open the modal
    const laravelUpdaterLink = document.querySelector('a[href="{{ route("admin.updater") }}"]');
    if (laravelUpdaterLink) {
        laravelUpdaterLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (updaterModal) {
                updaterModal.style.display = 'flex';
                updaterModal.setAttribute('aria-hidden', 'false');
            }
        });
    }

    if (closeUpdaterModalBtn) {
        closeUpdaterModalBtn.addEventListener('click', () => {
            if (updaterModal) {
                updaterModal.style.display = 'none';
                updaterModal.setAttribute('aria-hidden', 'true');
            }
        });
    }

    window.addEventListener('click', (event) => {
        if (updaterModal && event.target == updaterModal) {
            updaterModal.style.display = 'none';
            updaterModal.setAttribute('aria-hidden', 'true');
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
                    checkUpdateBtn.click(); // Refresh update status
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

        // Profile modal functionality
        const profileModal = document.getElementById('profileModal');
        const openProfileModalBtn = document.getElementById('openProfileModalBtn');
        const closeProfileModalBtn = document.getElementById('closeProfileModalBtn');

        if (openProfileModalBtn) {
            openProfileModalBtn.addEventListener('click', () => {
                if (profileModal) {
                    profileModal.style.display = "block";
                }
            });
        }

        if (closeProfileModalBtn) {
            closeProfileModalBtn.addEventListener('click', () => {
                if (profileModal) {
                    profileModal.style.display = "none";
                }
            });
        }

        // Subscription modal functionality
        // const subscriptionModal = document.getElementById('subscriptionModal'); // Removed duplicate declaration
        const openSubscriptionModalBtn = document.getElementById('openSubscriptionModalBtn');
        const closeSubscriptionModalBtn = document.getElementById('closeSubscriptionModalBtn');

        if (openSubscriptionModalBtn) {
            openSubscriptionModalBtn.addEventListener('click', () => {
                if (subscriptionModal) {
                    subscriptionModal.style.display = "block";
                    subscriptionModal.setAttribute('aria-hidden', 'false');
                }
            });
        }

        if (closeSubscriptionModalBtn) {
            closeSubscriptionModalBtn.addEventListener('click', () => {
                if (subscriptionModal) {
                    subscriptionModal.style.display = "none";
                    subscriptionModal.setAttribute('aria-hidden', 'true');
                }
            });
        }

        window.addEventListener('click', (event) => {
            if (event.target == subscriptionModal) {
                subscriptionModal.style.display = "none";
                subscriptionModal.setAttribute('aria-hidden', 'true');
            }
        });

        // Add Product Form Submission with Subscription Limit Check
        const addProductForm = document.getElementById('addProductForm');

        addProductForm.addEventListener('submit', function(event) {
            let subscriptionPlan = "{{ $tenant->subscription_plan ?? 'basic' }}".toLowerCase();
            const totalProducts = {{ $totalProducts ?? 0 }};
            const productLimitMap = {
                'basic': 5,
                'advance': 10,
                'pro': Infinity
            };

            const storedPlan = localStorage.getItem('selectedSubscriptionPlan');
            if (storedPlan) {
                subscriptionPlan = storedPlan;
            }

            if (totalProducts >= productLimitMap[subscriptionPlan]) {
                event.preventDefault();
                alert('Adding product out of limit, upgrade your plan');
                if (subscriptionModal) {
                    subscriptionModal.style.display = 'flex';
                    subscriptionModal.setAttribute('aria-hidden', 'false');
                }
            }
        });

        // User Accessibility modal functionality
        const userAccessibilityModal = document.getElementById('userAccessibilityModal');
        const openUserAccessibilityBtn = document.getElementById('openUserAccessibilityBtn');
        const closeUserAccessibilityModalBtn = document.getElementById('closeUserAccessibilityModalBtn');

        if (openUserAccessibilityBtn) {
            openUserAccessibilityBtn.addEventListener('click', () => {
                if (userAccessibilityModal) {
                    // Populate accessibility list dynamically
                    const accessibilityList = document.getElementById('accessibilityList');
                    accessibilityList.innerHTML = '';

                    // Example permissions - these should be dynamically set based on user data
                    const permissions = [];

                    // Assuming user permissions are passed as a global JS object or can be fetched
                    // For demonstration, hardcoded permissions are used here
                    @if(auth()->user())
                        @php
                            $user = auth()->user();
                            $canGenerateReports = $user->can_generate_reports ?? false;
                            $canSendEmail = $user->can_send_email ?? false;
                            $canEditSubscription = $user->can_edit_subscription ?? false;
                        @endphp
                        if (@json($canGenerateReports)) {
                            permissions.push('Can generate reports');
                        } else {
                            permissions.push('Cannot generate reports');
                        }
                        if (@json($canSendEmail)) {
                            permissions.push('Can send reports to email');
                        } else {
                            permissions.push('Cannot send reports to email');
                        }
                        if (@json($canEditSubscription)) {
                            permissions.push('Can edit subscription plan');
                        } else {
                            permissions.push('Cannot edit subscription plan');
                        }
                    @endif

                    permissions.forEach(permission => {
                        const li = document.createElement('li');
                        li.textContent = permission;
                        accessibilityList.appendChild(li);
                    });

                    userAccessibilityModal.style.display = "block";
                }
            });
        }

        if (closeUserAccessibilityModalBtn) {
            closeUserAccessibilityModalBtn.addEventListener('click', () => {
                if (userAccessibilityModal) {
                    userAccessibilityModal.style.display = "none";
                }
            });
        }

        window.addEventListener('click', (event) => {
            if (userAccessibilityModal && event.target == userAccessibilityModal) {
                userAccessibilityModal.style.display = "none";
            }
        });

        // Add User modal functionality
        const addUserModal = document.getElementById('addUserModal');
        const openAddUserModalBtn = document.getElementById('openAddUserModalBtn');
        const closeAddUserModalBtn = document.getElementById('closeAddUserModalBtn');

        if (openAddUserModalBtn) {
            openAddUserModalBtn.addEventListener('click', () => {
                if (addUserModal) {
                    addUserModal.style.display = "block";
                }
            });
        }

        if (closeAddUserModalBtn) {
            closeAddUserModalBtn.addEventListener('click', () => {
                if (addUserModal) {
                    addUserModal.style.display = "none";
                }
            });
        }

        window.addEventListener('click', (event) => {
            if (addUserModal && event.target == addUserModal) {
                addUserModal.style.display = "none";
            }
        });

        // Theme toggle button functionality
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const body = document.body;

        // Load saved theme from localStorage
        if (localStorage.getItem('theme') === 'dark') {
            body.style.backgroundColor = '#121212';
            body.style.color = '#e0e0e0';
        }

        themeToggleBtn.addEventListener('click', () => {
            if (body.style.backgroundColor === 'rgb(18, 18, 18)') {
                body.style.backgroundColor = '';
                body.style.color = '';
                localStorage.setItem('theme', 'light');
            } else {
                body.style.backgroundColor = '#121212';
                body.style.color = '#e0e0e0';
                localStorage.setItem('theme', 'dark');
            }
        });
        // Dropdown toggle script
        document.addEventListener('DOMContentLoaded', function () {
            const settingsBtn = document.getElementById('settingsBtn');
            const dropdown = document.getElementById('settingsDropdown');

            if (!settingsBtn) {
                console.error('settingsBtn element not found');
                return;
            }
            if (!dropdown) {
                console.error('settingsDropdown element not found');
                return;
            }

            settingsBtn.addEventListener('click', () => {
                console.log('settingsBtn clicked');
                dropdown.classList.toggle('show');
                const expanded = settingsBtn.getAttribute('aria-expanded') === 'true';
                settingsBtn.setAttribute('aria-expanded', !expanded);
            });
        });

        // Add Product Modal
        const addModal = document.getElementById('addProductModal');
        const openAddModalBtn = document.getElementById('openModalBtn');
        const closeAddModalBtn = document.getElementById('closeAddModalBtn');

        // Declare subscriptionModal once here
        const subscriptionModal = document.getElementById('subscriptionModal');

        if (openAddModalBtn) {
            openAddModalBtn.addEventListener('click', () => {
                const subscriptionPlan = "{{ $tenant->subscription_plan ?? 'basic' }}".toLowerCase();
                const totalProducts = {{ $totalProducts ?? 0 }};
const productLimitMap = {
    'basic': 5,
    'advance': 10,
    'pro': Infinity
};

if (totalProducts >= productLimitMap[subscriptionPlan] && (subscriptionPlan === 'basic' || subscriptionPlan === 'advance')) {
                    alert('Adding product out of limit, upgrade your plan');
                    if (subscriptionModal) {
                        subscriptionModal.style.display = 'flex';
                        subscriptionModal.setAttribute('aria-hidden', 'false');
                    }
                } else {
                    addModal.style.display = 'flex';
                    addModal.setAttribute('aria-hidden', 'false');
                }
            });
        }

        // Override subscriptionPlan with localStorage value if present
        document.addEventListener('DOMContentLoaded', () => {
            const subscriptionPlanDisplay = document.getElementById('openSubscriptionModalBtn');
            const storedPlan = localStorage.getItem('selectedSubscriptionPlan');
            if (storedPlan) {
                if (subscriptionPlanDisplay) {
                    subscriptionPlanDisplay.textContent = 'Subscription Plan: ' + storedPlan.toUpperCase();
                }
            }
        });

        // Update localStorage when plan is selected
        document.addEventListener('DOMContentLoaded', function () {
            const basicPlan = document.getElementById('basicPlan');
            const advancedPlan = document.getElementById('advancedPlan');
            const proPlan = document.getElementById('proPlan');
            const subscriptionPlanDisplay = document.getElementById('openSubscriptionModalBtn');

            function updatePlan(plan) {
                alert(plan.toUpperCase() + ' Plan selected.');
                localStorage.setItem('selectedSubscriptionPlan', plan.toLowerCase());
                if (subscriptionPlanDisplay) {
                    subscriptionPlanDisplay.textContent = 'Subscription Plan: ' + plan.toUpperCase();
                }
                // Reload the page to reflect updated subscription plan from server
                location.reload();
            }

            basicPlan.addEventListener('click', function () {
                updatePlan('basic');
            });

            advancedPlan.addEventListener('click', function () {
                updatePlan('advanced');
            });

            proPlan.addEventListener('click', function () {
                updatePlan('pro');
            });
        });

        // Override subscriptionPlan variable used in product limit check with localStorage value if present
        const originalSubscriptionPlan = "{{ $tenant->subscription_plan ?? 'basic' }}".toLowerCase();
        let subscriptionPlan = originalSubscriptionPlan;
        const storedPlan = localStorage.getItem('selectedSubscriptionPlan');
        if (storedPlan) {
            subscriptionPlan = storedPlan;
        }

        if (closeAddModalBtn) {
            closeAddModalBtn.addEventListener('click', () => {
                if (addModal) {
                    addModal.style.display = 'none';
                    addModal.setAttribute('aria-hidden', 'true');
                }
            });
        }

        window.addEventListener('click', (event) => {
            if (event.target === addModal) {
                addModal.style.display = 'none';
                addModal.setAttribute('aria-hidden', 'true');
            }
        });

        // Edit Product Modal
        const editModal = document.getElementById('editProductModal');
        const closeEditModalBtn = document.getElementById('closeEditModalBtn');
        const editProductForm = document.getElementById('editProductForm');

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const product = JSON.parse(button.getAttribute('data-product'));

                // Fill form fields with product data
                editProductForm.action = `/` + product.tenant_id + `/products/` + product.id;
                document.getElementById('edit_name').value = product.name;
                document.getElementById('edit_brand').value = product.brand;
                document.getElementById('edit_stock_quantity').value = product.stock_quantity;
                document.getElementById('edit_expiration_date').value = product.expiration_date ? product.expiration_date.split(' ')[0] : '';

                editModal.style.display = 'block';
                editModal.setAttribute('aria-hidden', 'false');
            });
        });

        closeEditModalBtn.addEventListener('click', () => {
            editModal.style.display = 'none';
            editModal.setAttribute('aria-hidden', 'true');
        });

        window.addEventListener('click', (event) => {
            if (event.target === editModal) {
                editModal.style.display = 'none';
                editModal.setAttribute('aria-hidden', 'true');
            }
        });

        
    </script>

    <!-- Pharmacists Modal -->
    <div id="pharmacistsModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="pharmacistsModalTitle" aria-modal="true" style="display:none;">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close" id="closePharmacistsModalBtn" aria-label="Close">&times;</span>
            <h2 id="pharmacistsModalTitle">Pharmacists List</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pharmacists as $pharmacist)
                    <tr>
                        <td>{{ $pharmacist->name }}</td>
                        <td>{{ $pharmacist->email }}</td>
                        <td>
                            <a href="{{ route('pharmacists.edit', ['pharmacist' => $pharmacist->id, 'tenant' => request()->route('tenant')]) }}" style="margin-right: 5px; color: #007bff; cursor: pointer;">Edit</a>
                            <form action="{{ route('pharmacists.destroy', ['pharmacist' => $pharmacist->id, 'tenant' => request()->route('tenant')]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openPharmacistsModalBtn = document.getElementById('openPharmacistsModalBtn');
            const pharmacistsModal = document.getElementById('pharmacistsModal');
            const closePharmacistsModalBtn = document.getElementById('closePharmacistsModalBtn');

            openPharmacistsModalBtn.addEventListener('click', function (e) {
                e.preventDefault();
                pharmacistsModal.style.display = 'block';
                pharmacistsModal.setAttribute('aria-hidden', 'false');
            });

            closePharmacistsModalBtn.addEventListener('click', function () {
                pharmacistsModal.style.display = 'none';
                pharmacistsModal.setAttribute('aria-hidden', 'true');
            });

            window.addEventListener('click', function (event) {
                if (event.target === pharmacistsModal) {
                    pharmacistsModal.style.display = 'none';
                    pharmacistsModal.setAttribute('aria-hidden', 'true');
                }
            });
        });
    </script>

    <!-- Footer copied from central admin dashboard -->
    <footer style="flex-shrink: 0; margin-top: 2rem; font-size: 0.9em; color: #666; text-align: center;">
        <div>
            Date and Time: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
        </div>
        <div>
            Laravel Version: {{ app()->version() }}
        </div>
    </footer>
</body>
</html>
