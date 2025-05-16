<!DOCTYPE html>
<html>
<head>
    <title>Edit Central Admin Profile</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 1rem; font-weight: bold; }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 0.25rem;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 1.5rem;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            margin-bottom: 1rem;
            text-align: center;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Edit Profile</h2>

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

<form method="POST" action="{{ route('central.admin.profile.update') }}">
    @csrf
    @method('PUT')

    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required />

    <label for="password">New Password (leave blank to keep current)</label>
    <input id="password" type="password" name="password" />

    <label for="password_confirmation">Confirm New Password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" />

    <button type="submit">Update Profile</button>
</form>

</body>
</html>
