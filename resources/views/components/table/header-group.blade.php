@props([
'background' => 'bg-white dark:bg-slate-900',
])

<div {{ $attributes->merge(['class' => $background.' table-header-group']) }}>
    {{ $slot }}
</div>
