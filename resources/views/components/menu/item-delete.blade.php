<x-menu.close>
    <x-menu.item wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE" {{ $attributes }}>
        <x-icons icon="trash-can" class="size-3! text-red-600" />

        @if ($slot->isEmpty())
            Delete
        @else
            {{ $slot }}
        @endif
    </x-menu.item>
</x-menu.close>
