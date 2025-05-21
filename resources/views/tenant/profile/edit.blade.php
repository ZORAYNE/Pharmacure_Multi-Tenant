
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>

    <form method="POST" action="{{ route('tenant.profile.update', ['tenant' => request()->route('tenant')]) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required class="w-full border border-gray-300 rounded px-3 py-2">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full border border-gray-300 rounded px-3 py-2">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Profile</button>
    </form>
</div>
