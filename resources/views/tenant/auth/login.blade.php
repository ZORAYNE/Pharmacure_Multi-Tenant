@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tenant Login</h2>

    @if(session('connectionMessage'))
        <div style="color: green; margin-bottom: 1rem;">
            {{ session('connectionMessage') }}
        </div>
    @endif

    @if($errors->any())
        <div style="color: red; margin-bottom: 1rem;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.login') }}">
        @csrf

        <div>
            <label for="tenant">Tenant</label>
            <input id="tenant" type="text" name="tenant" value="{{ old('tenant', request()->query('tenant')) }}" required autofocus />
        </div>

        <div>
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required />
        </div>

        <div>
            <label for="password">Password</label>
            <div style="position: relative;">
                <input id="password" type="password" name="password" required style="padding-right: 40px;" />
                <button type="button" id="togglePassword" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #007bff;">Show</button>
            </div>
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>

    <div style="margin-top: 1rem;">
        <a href="{{ route('tenant.auth.google.redirect') }}" style="display: inline-block; background-color: #db4437; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none;">Login with Google</a>
    </div>

    <div style="margin-top: 1rem;">
        <a href="{{ route('tenant.register') }}">Register as a new tenant</a>
    </div>
</div>

<script>
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePasswordBtn.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordBtn.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            togglePasswordBtn.textContent = 'Show';
        }
    });
</script>
@endsection