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
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 0.5rem; margin-top: 0.25rem;
        }
        button {
            margin-top: 1.5rem;
            padding: 0.75rem 1.25rem;
            background-color: #28a745; color: white; border: none; border-radius: 4px;
        }
        .error { color: red; }
        .success { color: green; }
        #googleSignInBtn {
            margin-top: 1rem;
            background-color: #4285F4;
            color: white;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
    </style>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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

        <label for="full_name" style="display:none;">Full Name:</label>
        <input id="full_name" type="text" name="full_name" style="display:none;" value="{{ old('full_name') }}" required />

        <label for="email" style="display:none;">Email:</label>
        <input id="email" type="email" name="email" style="display:none;" value="{{ old('email') }}" required />

        <label for="password" style="display:none;">Password:</label>
        <input id="password" type="password" name="password" style="display:none;" required />

        <label for="password_confirmation" style="display:none;">Confirm Password:</label>
        <input id="password_confirmation" type="password" name="password_confirmation" style="display:none;" required />

        <button type="submit">Register</button>
    </form>

    <button id="googleSignInBtn">Sign in with Google</button>

    <script>
        function generateRandomPassword(length = 12) {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let password = "";
            for (let i = 0; i < length; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return password;
        }

        function handleCredentialResponse(response) {
            // Decode JWT token to get user info
            const data = JSON.parse(atob(response.credential.split('.')[1]));
            const fullName = data.name || "";
            const email = data.email || "";
            const password = generateRandomPassword();

            // Autofill hidden fields
            document.getElementById('full_name').value = fullName;
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;

            // Show alert or message to user
            alert("Google sign-in successful. Full Name and Email autofilled. Please enter Tenant Name and submit the form.");
        }

        window.onload = function() {
            google.accounts.id.initialize({
                client_id: 'YOUR_GOOGLE_CLIENT_ID',
                callback: handleCredentialResponse
            });

            document.getElementById('googleSignInBtn').onclick = function() {
                google.accounts.id.prompt();
            };
        };
    </script>
</body>
</html>
