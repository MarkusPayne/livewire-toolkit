<x-menu.close>
    <x-menu.item {{ $attributes }}>
        <x-icons icon="pen-to-square" class="text-primary-600" {{ $attributes->whereStartsWith('class') }} />
        @if ($slot->isEmpty())
            Edit
        @else
            {{ $slot }}
        @endif
    </x-menu.item>
</x-menu.close>
