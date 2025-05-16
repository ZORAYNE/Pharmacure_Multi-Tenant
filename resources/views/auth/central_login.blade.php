<!DOCTYPE html>
<html lang="en">
<head>
    <title>Central Admin Login</title>
    <meta charset="UTF-8" />
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; transition: background-color 0.3s, color 0.3s; }
        body.dark-theme { background-color: #121212; color: #e0e0e0; }
        .container { max-width: 400px; margin: auto; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); transition: background-color 0.3s, color 0.3s; }
        body.dark-theme .container { background: #1e1e1e; color: #e0e0e0; }
        h1 { text-align: center; color: #333; }
        body.dark-theme h1 { color: #e0e0e0; }
        label { display: block; margin-top: 1rem; }
        input { width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #ddd; border-radius: 4px; background: white; color: black; }
        body.dark-theme input { background: #333; color: #e0e0e0; border: 1px solid #555; }
        button { width: 100%; margin-top: 1rem; padding: 0.75rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; }
        .info { margin-top: 1rem; text-align: center; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .theme-toggle-btn {
            margin-top: 1rem;
            width: 100%;
            padding: 0.5rem;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .theme-toggle-btn:hover {
            background-color: #5a6268;
        }
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

        <button class="theme-toggle-btn" id="themeToggleBtn">Toggle Dark/Light Theme</button>

        <div class="info">
            <p>Want to be a tenant? <a href="{{ route('tenant.register') }}">Register here</a></p>
            <p>Are you a tenant? <a href="{{ route('tenant.login') }}">Tenant Login</a></p>
        </div>
    </div>

    <script>
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const currentTheme = localStorage.getItem('theme') || 'light';

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }
        }

        applyTheme(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            const newTheme = document.body.classList.contains('dark-theme') ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>
</html>
