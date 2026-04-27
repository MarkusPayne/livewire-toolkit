<x-toolkit::menu.close>
    <x-toolkit::menu.item {{ $attributes }}>
        <x-toolkit::icon.add class="size-3 text-green-600" />

        <span>{{ $slot ?? 'Add' }}</span>
    </x-toolkit::menu.item>
</x-toolkit::menu.close>
