@props([
    'mode' => null,
    'side' => null,
    'title' => null,
])
@php
    $mode = $mode ?? config('livewire-toolkit.sidebar.default_mode', 'slideover');
    $side = $side ?? config('livewire-toolkit.sidebar.default_side', 'left');

    $isLeft = $side === 'left';
    $closedTransform = $isLeft ? '-translate-x-full' : 'translate-x-full';
    $rowDirection = $isLeft ? 'flex-row' : 'flex-row-reverse';
    $desktopSide = $isLeft ? 'lg:left-0' : 'lg:right-0';
    $contentPad = $isLeft ? 'lg:pl-72' : 'lg:pr-72';
    $desktopBorder = $isLeft ? 'border-r' : 'border-l';
@endphp

@if ($mode === 'slideover')
    <div
        x-data="{ open: false }"
        x-on:toolkit-sidebar-open.window="open = true"
        x-on:toolkit-sidebar-close.window="open = false"
        x-on:keydown.escape.window="open = false"
        x-cloak x-show="open"
        {{ $attributes->class(['relative z-50']) }}
        role="dialog" aria-modal="true">
        <div
            x-show="open"
            x-transition:enter="transition-opacity duration-300 ease-linear"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300 ease-linear"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50"
            x-on:click="open = false"></div>

        <div class="fixed inset-0 flex {{ $rowDirection }} focus:outline-none">
            <div
                x-show="open"
                x-transition:enter="transform transition duration-300 ease-in-out"
                x-transition:enter-start="{{ $closedTransform }}"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition duration-300 ease-in-out"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="{{ $closedTransform }}"
                class="relative flex w-full max-w-96 flex-1">
                <div class="relative flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">
                    <div class="relative flex h-16 shrink-0 items-center justify-between pt-5">
                        {{ $logo ?? '' }}
                        <button type="button" x-on:click="open = false">
                            <span class="sr-only">Close sidebar</span>
                            <x-toolkit::icon.close />
                        </button>
                    </div>

                    <nav class="relative flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-1 font-normal text-gray-600">
                            {{ $nav ?? $slot }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@else
    <div
        x-data="{ open: false }"
        x-on:toolkit-sidebar-open.window="open = true"
        x-on:toolkit-sidebar-close.window="open = false"
        x-on:keydown.escape.window="open = false"
        {{ $attributes->class(['min-h-screen']) }}>
        {{-- Mobile slide-over --}}
        <div x-cloak x-show="open" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div
                x-show="open"
                x-transition:enter="transition-opacity duration-300 ease-linear"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-300 ease-linear"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/80"
                x-on:click="open = false"></div>

            <div class="fixed inset-0 flex {{ $rowDirection }}">
                <div
                    x-show="open"
                    x-transition:enter="transform transition duration-300 ease-in-out"
                    x-transition:enter-start="{{ $closedTransform }}"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition duration-300 ease-in-out"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="{{ $closedTransform }}"
                    class="relative flex w-full max-w-xs flex-1">
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                        <div class="relative flex h-16 shrink-0 items-center justify-between">
                            {{ $logo ?? '' }}
                            <button type="button" x-on:click="open = false">
                                <span class="sr-only">Close sidebar</span>
                                <x-toolkit::icon.close />
                            </button>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col gap-y-1 font-normal text-gray-600">
                                {{ $nav ?? '' }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desktop persistent column --}}
        <div class="hidden lg:fixed lg:inset-y-0 {{ $desktopSide }} lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto {{ $desktopBorder }} border-gray-200 bg-white px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    {{ $logo ?? '' }}
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-1 font-normal text-gray-600">
                        {{ $nav ?? '' }}
                    </ul>
                </nav>
            </div>
        </div>

        {{-- Main content area --}}
        <div class="{{ $contentPad }}">
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" x-on:click="open = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    {{ $topbar ?? '' }}
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if ($title)
                        <h1 class="pb-6 text-2xl font-semibold text-gray-900">{{ $title }}</h1>
                    @endif
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
@endif
