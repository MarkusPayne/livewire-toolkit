@props([
    'title' => null,
])
<div x-data="{ sidebarOpen: false }" class="min-h-screen">
    {{-- Mobile slide-over sidebar --}}
    <div x-cloak x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
        <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity duration-300 ease-linear"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-300 ease-linear"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/80"
                @click="sidebarOpen = false"></div>

        <div class="fixed inset-0 flex">
            <div
                    x-show="sidebarOpen"
                    x-transition:enter="transform transition duration-300 ease-in-out"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition duration-300 ease-in-out"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="relative mr-16 flex w-full max-w-xs flex-1">
                {{-- Close button --}}
                <div
                        x-show="sidebarOpen"
                        x-transition:enter="duration-300 ease-in-out"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="duration-300 ease-in-out"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute top-0 left-full flex w-16 justify-center pt-3 pr-5 -ml-10">
                    <button type="button" x-on:click="sidebarOpen = false" class="">
                        <span class="sr-only">Close sidebar</span>
                        <x-toolkit::icon.close class="size-6 " />
                    </button>
                </div>

                {{-- Mobile sidebar content --}}
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                    <div class="flex h-16 shrink-0 items-center">
                        {{ $logo ?? '' }}
                    </div>
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-1">
                            {{ $nav ?? '' }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Desktop persistent sidebar --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
            <div class="flex h-16 shrink-0 items-center">
                {{ $logo ?? '' }}
            </div>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-1">
                    {{ $nav ?? '' }}
                </ul>
            </nav>
        </div>
    </div>

    {{-- Main content --}}
    <div class="lg:pl-72">
        {{-- Top bar with hamburger (mobile only) --}}
        <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <button type="button" x-on:click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            {{-- Separator (mobile only) --}}
            <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

            {{-- Top bar content --}}
            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                {{ $topbar ?? '' }}
            </div>
        </div>

        {{-- Page content --}}
        <main class="py-10">
            <div class="px-4 sm:px-6 lg:px-8">
                @if ($title)
                    <h1 class="text-2xl font-semibold text-gray-900 pb-6">{{ $title }}</h1>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>
</div>
