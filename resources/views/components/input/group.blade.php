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

<div {{ $attributes->merge(["class" => "col-span-12 " . $colSpan]) }}>
    @if ($label)
        <label
            @if ($for)
                for="{{ $for }}"
            @endif
            class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">
            {!! $label !!}
        </label>
    @endif

    {{ $slot }}

    <x-toolkit::input.error :error="$error" />

    @if ($helpText)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
</div>
