@props([
'background' => 'bg-white',
])

<div {{ $attributes->merge(['class' => $background.' table-header-group']) }}>
    {{ $slot }}
</div>
