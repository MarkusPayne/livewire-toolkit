@props([
    "label" => false,
    "for" => false,
    "error" => false,
    "helpText" => false,
    "size" => "6",
])

@php
    $colSpan = match ((int) $size) {
        1 => "md:col-span-1",
        2 => "md:col-span-2",
        3 => "md:col-span-3",
        4 => "md:col-span-4",
        5 => "md:col-span-5",
        7 => "md:col-span-7",
        8 => "md:col-span-8",
        9 => "md:col-span-9",
        10 => "md:col-span-10",
        11 => "md:col-span-11",
        12 => "md:col-span-12",
        default => "md:col-span-6",
    };
@endphp

<div {{ $attributes->merge(["class" => $colSpan . " sm:flex-col justify-center py-2 "]) }}>
    <div class="items-start justify-between gap-x-3 md:flex">
        @if ($label)
            <label
                @if ($for)
                    for="{{ $for }}"
                @endif
                class="flex flex-row-reverse text-sm font-medium text-gray-600 dark:text-gray-100">
                {!! $label !!}:
            </label>
        @endif

        <div class="mt-1 flex min-w-50 items-center justify-end sm:mt-0">
            {{ $slot }}
        </div>
    </div>

    <x-toolkit::input.error :error="$error" :inline="true" class="mt-1 text-right" />

    @if ($helpText)
        <p class="mt-1 text-right text-xs text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
</div>
