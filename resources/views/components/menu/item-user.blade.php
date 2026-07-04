<x-toolkit::menu.close>
    <x-toolkit::menu.item {{ $attributes }}>
        <x-toolkit::icon.user class="size-3 text-sky-600" />

            {{ $slot ?? ''}}

    </x-toolkit::menu.item>
</x-toolkit::menu.close>
