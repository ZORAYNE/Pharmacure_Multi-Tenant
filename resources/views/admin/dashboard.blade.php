<!DOCTYPE html>
<html>
<head>
    <title>Central App Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }
        body {
            /* Remove margin, padding moved to container */
        }
        .container {
            flex: 1 0 auto;
            padding: 2rem;
            display: flex;
            flex-direction: column;
        }
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
        .password-toggle {
            position: relative;
            display: inline-block;
        }
        .password-toggle input[type="password"],
        .password-toggle input[type="text"] {
            padding-right: 30px;
        }
        .password-toggle .toggle-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            color: #666;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 420px;
            border-radius: 8px;
            position: relative;
        }
        .close {
            color: #aaa;
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        /* Table form styles */
        .form-table {
            width: 100%;
            border-collapse: collapse;
        }
        .form-table td {
            padding: 8px;
            vertical-align: middle;
        }
        .form-table td.label-cell {
            width: 35%;
            font-weight: bold;
            text-align: right;
            padding-right: 12px;
        }
        .form-table td.input-cell input {
            width: 100%;
            padding: 6px 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-table td.input-cell .password-toggle {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .form-table td.input-cell .password-toggle input {
            width: 100%;
            padding-right: 30px;
        }
        .form-table td.input-cell .password-toggle .toggle-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            color: #666;
        }
        .form-table button[type="submit"] {
            margin-top: 1rem;
            padding: 0.75rem 1.25rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .form-table button[type="submit"]:hover {
            background-color: #218838;
        }
        footer {
            flex-shrink: 0;
            margin-top: 2rem;
            font-size: 0.9em;
            color: #666;
        }
        .btn {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        font-weight: 600;
        margin-right: 0.5rem;
    }
    .accept-btn {
        background-color: #28a745; /* Green */
    }
    .delete-btn, .disable-btn {
        background-color: #dc3545; /* Red */
    }
    .edit-btn {
        background-color: #007bff; /* Blue */
    }
    .status {
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
        color: white;
        display: inline-block;
    }
    .status.accepted {
        background-color: #28a745; /* Green */
    }
    .status.pending {
        background-color: #007bff; /* Blue */
    }
    </style>
</head>
<body>

<!-- Navbar -->
<nav style="background-color: #007bff; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center;">
    <div style="font-size: 20px; font-weight: bold;">Central Admin Dashboard</div>
    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="position: relative;">
            <button id="themeToggleBtn" type="button" style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">Toggle Light/Dark Theme</button>
        </div>
        <div style="position: relative;">
            <button id="settingsBtn" style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">Settings &#x25BC;</button>
            <div id="settingsDropdown" style="display: none; position: absolute; right: 0; background: white; color: black; border: 1px solid #ccc; border-radius: 4px; min-width: 160px; box-shadow: 0 2px 5px rgba(0,0,0,0.15); z-index: 1001;">
                <a href="#" id="openUpdaterModalBtnDropdown" style="display: block; padding: 10px 15px; text-decoration: none; color: black; cursor: pointer;">Laravel Updater</a>
                <a href="#" id="openProfileModalBtn" style="display: block; padding: 10px 15px; text-decoration: none; color: black; cursor: pointer;">Profile</a>

            <!-- Profile Modal -->
            <div id="profileModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeProfileModalBtn">&times;</span>
                    <h2>Profile Information</h2>
                    <p>This is a popup message modal for the Profile button.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('central.admin.logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" style="width: 100%; padding: 10px 15px; border: none; background: none; text-align: left; cursor: pointer; color: black;">Logout</button>
            </form>
        </div>
    </div>
</nav>

<script>
    // Profile modal functionality
    const profileModal = document.getElementById('profileModal');
    const openProfileModalBtn = document.getElementById('openProfileModalBtn');
    const closeProfileModalBtn = document.getElementById('closeProfileModalBtn');

    if (openProfileModalBtn) {
        openProfileModalBtn.onclick = function() {
            if (profileModal) {
                profileModal.style.display = "block";
            }
        }
    }

    if (closeProfileModalBtn) {
        closeProfileModalBtn.onclick = function() {
            if (profileModal) {
                profileModal.style.display = "none";
            }
        }
    }

    window.onclick = function(event) {
        if (profileModal && event.target == profileModal) {
            profileModal.style.display = "none";
        }
    }

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
</script>

<div class="container">
    <h2>Registered Tenants</h2>
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button id="openModalBtn" style="width: 2.25in;">Register New Tenant</button>

    <table>
        <thead>
            <tr>
                <th>Tenant Name</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Date Created</th>
                <th>Status</th>
                <th>Action</th>
                <th>Tenant Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenants as $tenant)
                <tr>
                    <td>{{ $tenant->tenant_name }}</td>
                    <td>{{ $tenant->full_name }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td>{{ $tenant->created_at->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($tenant->status) }}</td>
    <td>
        @if($tenant->status === 'pending')
            <form method="POST" action="{{ route('admin.tenants.accept', $tenant->tenant_name) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn accept-btn">Accept</button>
            </form>
            <form method="POST" action="{{ route('admin.tenants.delete', $tenant->tenant_name) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this tenant?')">Delete</button>
            </form>
        @elseif($tenant->status === 'accepted')
            <form method="POST" action="{{ route('admin.tenants.revert', $tenant->tenant_name) }}" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn disable-btn">Disable</button>
            </form>
        @endif
        <a href="{{ route('admin.tenants.edit', $tenant->tenant_name) }}" class="btn edit-btn">Edit</a>
    </td>
    <td>
        @if($tenant->status === 'accepted')
        <a href="{{ url('/login', [], false) }}?tenant={{ $tenant->tenant_name }}" target="_blank" class="button-link">Tenant Login</a>
        @else
            <span style="color: grey;">Not available</span>
        @endif
    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="tenantRegisterModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <h2>Register New Tenant</h2>
        <form method="POST" action="{{ route('admin.tenants.store') }}">
            @csrf
            <table class="form-table">
                <tr>
                    <td class="label-cell"><label for="tenant_name">Tenant Name (DB):</label></td>
                    <td class="input-cell"><input id="tenant_name" type="text" name="tenant_name" required /></td>
                </tr>
                <tr>
                    <td class="label-cell"><label for="full_name">Full Name</label></td>
                    <td class="input-cell"><input id="full_name" type="text" name="full_name" required /></td>
                </tr>
                <tr>
                    <td class="label-cell"><label for="email">Email</label></td>
                    <td class="input-cell"><input id="email" type="email" name="email" required /></td>
                </tr>
                <tr>
                    <td class="label-cell"><label for="password">Password</label></td>
                    <td class="input-cell">
                        <div class="password-toggle">
                            <input id="password" type="password" name="password" required />
                            <span class="toggle-icon" onclick="togglePasswordVisibility('password')">Show</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label-cell"><label for="password_confirmation">Confirm Password</label></td>
                    <td class="input-cell">
                        <div class="password-toggle">
                            <input id="password_confirmation" type="password" name="password_confirmation" required />
                            <span class="toggle-icon" onclick="togglePasswordVisibility('password_confirmation')">Show</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit">Register Tenant</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const toggleIcon = input.nextElementSibling;
        if (input.type === "password") {
            input.type = "text";
            toggleIcon.textContent = "Hide";
        } else {
            input.type = "password";
            toggleIcon.textContent = "Show";
        }
    }

    // Modal functionality
    const modal = document.getElementById('tenantRegisterModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    if (openModalBtn) {
        openModalBtn.onclick = function() {
            if (modal) {
                modal.style.display = "block";
            }
        }
    }

    if (closeModalBtn) {
        closeModalBtn.onclick = function() {
            if (modal) {
                modal.style.display = "none";
            }
        }
    }

    window.onclick = function(event) {
        if (modal && event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<!-- Updater Modal -->
<div id="updaterModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeUpdaterModalBtn">&times;</span>
        <h2>Laravel Updater</h2>
        <div class="info">
            <p>Current Version: <span id="currentVersion">{{ $currentVersion }}</span></p>
            <p>Latest Version: <span id="latestVersion">{{ $latestVersion ?? 'Unknown' }}</span></p>
        </div>
        <button id="checkUpdateBtn">Check for Updates</button>
        <button id="performUpdateBtn" disabled>Perform Update</button>
        <div id="updaterMessage"></div>
    </div>
</div>


<!-- Move footer to the bottom of the page -->
<footer>
    <div>
        Date and Time: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
    </div>
    <div>
        Laravel Version: {{ app()->version() }}
    </div>
</footer>
</body>
</html>

@if(session('tenantAccepted'))
<x-modal id="tenantAcceptedModal">
    <p>Tenant has been accepted, the tenant link has been sent to the email.</p>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('tenantAcceptedModal');
        if (modal) {
            modal.style.display = 'block';
        }
    });
</script>
@endif
<script>
    //Laravel Updater Modal
    const updaterModal = document.getElementById('updaterModal');
    const openUpdaterModalBtn = document.getElementById('openUpdaterModalBtn');
    const closeUpdaterModalBtn = document.getElementById('closeUpdaterModalBtn');
    const checkUpdateBtn = document.getElementById('checkUpdateBtn');
    const performUpdateBtn = document.getElementById('performUpdateBtn');
    const updaterMessage = document.getElementById('updaterMessage');
    const latestVersionSpan = document.getElementById('latestVersion');

    if (openUpdaterModalBtn) {
        openUpdaterModalBtn.onclick = function() {
            if (updaterModal) {
                updaterModal.style.display = 'block';
            }
        }
    }

    if (closeUpdaterModalBtn) {
        closeUpdaterModalBtn.onclick = function() {
            if (updaterModal) {
                updaterModal.style.display = 'none';
            }
        }
    }

    window.onclick = function(event) {
        if (updaterModal && event.target == updaterModal) {
            updaterModal.style.display = 'none';
        }
    }

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

    // Settings dropdown toggle
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsDropdown = document.getElementById('settingsDropdown');

    if (settingsBtn) {
        settingsBtn.addEventListener('click', () => {
            if (settingsDropdown.style.display === 'block') {
                settingsDropdown.style.display = 'none';
            } else {
                settingsDropdown.style.display = 'block';
            }
        });
    }

    window.addEventListener('click', (event) => {
        if (!settingsBtn.contains(event.target) && !settingsDropdown.contains(event.target)) {
            settingsDropdown.style.display = 'none';
        }
    });

    // Open updater modal from dropdown link
    const openUpdaterModalBtnDropdown = document.getElementById('openUpdaterModalBtnDropdown');
    if (openUpdaterModalBtnDropdown) {
        openUpdaterModalBtnDropdown.addEventListener('click', (e) => {
            e.preventDefault();
            updaterModal.style.display = 'block';
            settingsDropdown.style.display = 'none';
        });
    }
</script>

</body>
</html>
