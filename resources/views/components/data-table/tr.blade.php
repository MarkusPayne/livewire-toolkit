@props([
    'hasError' => false,
    'hasWarning' => false,
    'hasSuccess' => false,
    'textColor' => false,
])

<div
    {{ $attributes->class(['table-row border-b border-gray-200 align-middle hover:bg-gray-100 dark:border-gray-700', 'text-[' . $textColor . ']' => $textColor, 'bg-red-100!' => $hasError, 'bg-yellow-100' => $hasWarning, 'bg-green-400' => $hasSuccess]) }}>
    {{ $slot }}
</div>
