<x-toolkit::menu.close>
    <x-toolkit::menu.item wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE" {{ $attributes }}>
        <x-toolkit::icon.delete class="size-3 text-red-600" />

        @if ($slot->isEmpty())
            Delete
        @else
            {{ $slot }}
        @endif
    </x-toolkit::menu.item>
</x-toolkit::menu.close>
