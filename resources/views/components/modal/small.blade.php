@props([
    'name' => 'small-modal',
])
<x-toolkit::modal.index :name="$name" maxWidth="sm:max-w-lg" {{ $attributes }}>
    @if (isset($title))
        <x-slot:title>{{ $title }}</x-slot:title>
    @endif

    {{ $slot }}

    @if (isset($footer))
        <x-slot:footer>{{ $footer }}</x-slot:footer>
    @endif
</x-toolkit::modal.index>
