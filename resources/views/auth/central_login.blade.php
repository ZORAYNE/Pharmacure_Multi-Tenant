<!DOCTYPE html>
<html lang="en">
<head>
    <title>Central Admin Login</title>
    <meta charset="UTF-8" />
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 400px; margin: auto; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #333; }
        label { display: block; margin-top: 1rem; }
        input { width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; margin-top: 1rem; padding: 0.75rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; }
        .info { margin-top: 1rem; text-align: center; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Central Admin Login</h1>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('central.admin.login') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required autofocus />

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />

            <button type="submit">Login</button>
        </form>

        <div class="info">
            <p>Want to be a tenant? <a href="{{ route('tenant.register') }}">Register here</a></p>
            <p>Are you a tenant? <a href="{{ route('tenant.login') }}">Tenant Login</a></p>
        </div>
    </div>
</body>
</html>
