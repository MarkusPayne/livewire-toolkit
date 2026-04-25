<x-menu.close>
    <x-menu.item {{ $attributes }}>
        <x-icons icon="plus" class="size-3! text-green-600" />

        <span>{{ $slot ?? 'Add' }}</span>
    </x-menu.item>
</x-menu.close>
