<div {{ $attributes->merge(['class' => 'relative']) }}>
    <div>
        {{ $trigger }}
    </div>

    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
        {{ $slot }}
    </div>
</div>
