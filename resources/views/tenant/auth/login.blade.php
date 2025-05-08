<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Login</title>
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
        <h1>Tenant Login</h1>

        <form method="POST" action="{{ route('tenant.login') }}">
    @csrf

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember Me</label>
    </div>

    <button type="submit">Login</button>
</form>

@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    </div>
</body>
</html>
