@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin: 2rem auto;">
    <h2>Edit Tenant: {{ $tenant->tenant_name }}</h2>

    @if ($errors->any())
        <div class="error" style="color: red; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tenants.update', $tenant->tenant_name) }}">
        @csrf
        @method('PATCH')

        <div style="margin-bottom: 1rem;">
            <label for="full_name" style="display: block; font-weight: bold;">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $tenant->full_name) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="email" style="display: block; font-weight: bold;">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $tenant->email) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="password" style="display: block; font-weight: bold;">Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password" style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="password_confirmation" style="display: block; font-weight: bold;">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" style="background-color: #007bff; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer;">Update Tenant</button>
        <a href="{{ route('admin.dashboard') }}" style="margin-left: 1rem; color: #007bff; text-decoration: underline;">Cancel</a>
    </form>
</div>
@endsection
