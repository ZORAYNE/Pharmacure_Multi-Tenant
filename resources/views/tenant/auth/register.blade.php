<!DOCTYPE html>
<html>
<head>
    <title>Tenant Registration</title>
    <meta charset="UTF-8" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 400px; }
        label { display: block; margin-top: 1rem; }
        input { width: 100%; padding: 0.5rem; margin-top: 0.25rem; }
        button { margin-top: 1rem; padding: 0.75rem 1.25rem; background-color: #28a745; color: white; border: none; border-radius: 4px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Tenant Registration</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.register') }}">
        @csrf
        <label for="tenant_name">Tenant Name (DB):</label>
        <input id="tenant_name" name="tenant_name" type="text" required />

        <label for="full_name">Full Name:</label>
        <input id="full_name" name="full_name" type="text" required />

        <label for="email">Email:</label>
        <input id="email" name="email" type="email" required />

        <label for="password">Password:</label>
        <input id="password" name="password" type="password" required />

        <label for="password_confirmation">Confirm Password:</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required />

        <button type="submit">Register</button>
    </form>

    <p>Already registered? <a href="{{ route('tenant.login') }}">Login here</a></p>
</body>
</html>