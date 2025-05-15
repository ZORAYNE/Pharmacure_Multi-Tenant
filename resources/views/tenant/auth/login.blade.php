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
            <input id="password" type="password" name="password" required />
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</div>
@endsection
