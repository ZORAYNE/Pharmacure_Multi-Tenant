<!DOCTYPE html>
<html>
<head>
    <title>Tenant Self Registration</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        form { max-width: 400px; }
        label { display: block; margin-top: 1rem; }
        input[type=text], input[type=email], input[type=password], select {
            width: 100%; padding: 0.5rem; margin-top: 0.25rem;
        }
        button {
            margin-top: 1.5rem;
            padding: 0.75rem 1.25rem;
            background-color: #28a745; color: white; border: none; border-radius: 4px;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Tenant Self Registration</h1>

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

    <form method="POST" action="{{ route('tenant.register') }}" id="registrationForm">
        @csrf
        <label for="tenant_name">Tenant Name (DB):</label>
        <input id="tenant_name" type="text" name="tenant_name" value="{{ old('tenant_name') }}" required />

        <label for="full_name">Full Name:</label>
        <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required />

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required />

        <label for="password">Password:</label>
        <input id="password" type="password" name="password" required />

        <label for="password_confirmation">Confirm Password:</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required />

        <label for="subscription_plan" style="display:block; font-weight:bold; margin-top:1rem;">Subscription Plan</label>
        <select id="subscription_plan" name="subscription_plan" required>
            <option value="basic" {{ old('subscription_plan') == 'basic' ? 'selected' : '' }}>Basic</option>
            <option value="advance" {{ old('subscription_plan') == 'advance' ? 'selected' : '' }}>Advance</option>
            <option value="pro" {{ old('subscription_plan') == 'pro' ? 'selected' : '' }}>Pro</option>
        </select>

        <button type="submit">Register</button>
    </form>
</body>
</html>
