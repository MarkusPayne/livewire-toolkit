<x-toolkit::menu.close>
    <x-toolkit::menu.item {{ $attributes }}>
        <x-toolkit::icon.preview class="size-3 text-sky-600" />
        @if ($slot->isEmpty())
            Preview
        @else
            {{ $slot }}
        @endif
    </x-toolkit::menu.item>
</x-toolkit::menu.close>
