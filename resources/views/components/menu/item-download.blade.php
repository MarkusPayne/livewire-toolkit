<x-menu.close>
    <x-menu.item {{ $attributes }}>
        <x-icons icon="folder-arrow-down" class="size-3! text-primary-600" />
        @if ($slot->isEmpty())
            Download
        @else
            {{ $slot }}
        @endif
    </x-menu.item>
</x-menu.close>
