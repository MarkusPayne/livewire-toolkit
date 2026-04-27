@props([
    'src' => null,
    'alt' => null,
])
<div>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt ?? config('app.name') }}" {{ $attributes->merge(['class' => 'mx-auto h-30 w-auto']) }} />
    @else
        <span {{ $attributes->merge(['class' => 'text-lg font-bold']) }}>{{ $alt ?? config('app.name') }}</span>
    @endif
</div>
