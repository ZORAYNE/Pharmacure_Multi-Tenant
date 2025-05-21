<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Central Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            background-color: #1f2937;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .card h2 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: white;
            font-weight: bold;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 0.5rem;
            color: #e5e7eb;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            border-radius: 0.375rem;
            border: none;
            background-color: #e5e7eb;
            margin-bottom: 1rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.6rem;
            background-color: #3b82f6;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: #2563eb;
        }

        .links {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #d1d5db;
        }

        .links a {
            color: #3b82f6;
            text-decoration: none;
            margin-left: 0.25rem;
        }

        .toggle-btn {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            background-color: #4b5563;
            color: white;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <button class="toggle-btn" onclick="toggleTheme()">Toggle Dark/Light Theme</button>

    <div class="card">
        <h2>Central Admin Login</h2>
        <form method="POST" action="{{ route('central.admin.login') }}">
            @csrf
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required value="{{ old('email') }}">
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button class="btn-login" type="submit">Login</button>
        </form>

        <div class="links">
            <p>Want to be a tenant?
                <a href="{{ route('tenant.register') }}">Register here</a>
            </p>
            <p>Are you a tenant?
                <a href="{{ route('tenant.login') }}">Tenant Login</a>
            </p>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            if (html.getAttribute('data-theme') === 'dark') {
                html.setAttribute('data-theme', 'light');
                document.body.style.backgroundColor = '#f9fafb';
            } else {
                html.setAttribute('data-theme', 'dark');
                document.body.style.backgroundColor = '#111827';
            }
        }
    </script>

</body>
</html>
