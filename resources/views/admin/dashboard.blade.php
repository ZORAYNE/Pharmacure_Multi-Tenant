<!DOCTYPE html>
<html>
<head>
    <title>Tenant Dashboard</title>
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

<h2>Register New Tenant</h2>
<form method="POST" action="{{ route('admin.tenants.store') }}">
    @csrf
    <label for="tenant_name">Tenant Name (DB):</label>
    <input id="tenant_name" type="text" name="tenant_name" required />

    <label for="full_name">Full Name</label>
    <input id="full_name" type="text" name="full_name" required />

    <label for="email">Email</label>
    <input id="email" type="email" name="email" required />

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required />

    <label for="password_confirmation">Confirm Password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" required />

    <button type="submit">Register Tenant</button>
</form>

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
                        <form method="POST" action="{{ route('admin.tenants.accept', $tenant->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('admin.tenants.delete', $tenant->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this tenant?')">Delete</button>
                        </form>
                    @elseif($tenant->status === 'accepted')
                        <form method="POST" action="{{ route('admin.tenants.revert', $tenant->id) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Disable (Revert to Pending)</button>
                        </form>
                    @endif
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

</body>
</html>