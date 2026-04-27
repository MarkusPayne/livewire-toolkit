<div x-cloak x-show="sidebarOpen" {{ $attributes->class(['relative z-50']) }} role="dialog" aria-modal="true">
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity duration-300 ease-linear"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300 ease-linear"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50"
        @click="sidebarOpen = false"></div>

    <div class="fixed inset-0 flex flex-row-reverse focus:outline-none">
        <div
            x-show="sidebarOpen"
            x-on:click.away="sidebarOpen = false"
            x-transition:enter="transform transition duration-300 ease-in-out"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition duration-300 ease-in-out"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="relative flex w-full max-w-96 flex-1">
            <div class="relative flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">
                <div class="relative flex h-16 shrink-0 items-center justify-between pt-5">
                    {{ $logo ?? '' }}
                    <button type="button" x-on:click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <x-toolkit::icon.close />
                    </button>
                </div>

                <nav class="relative flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-1 font-normal text-gray-600">
                        {{ $slot }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
