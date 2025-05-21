<!DOCTYPE html>
<html>
<head>
    <title>Add Pharmacist</title>
    <meta charset="UTF-8" />
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
    </style>
</head>
<body>
    <h1>Add Pharmacist</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pharmacists.store') }}">
        @csrf
        <label for="full_name">Full Name:</label>
        <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required />

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required />

        <label for="password">Password:</label>
        <input id="password" type="password" name="password" required />

        <label for="password_confirmation">Confirm Password:</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required />

        <label for="certification_number">Certification Number:</label>
        <input id="certification_number" type="text" name="certification_number" value="{{ old('certification_number') }}" required />

        <button type="submit">Add Pharmacist</button>
    </form>
</body>
</html>
