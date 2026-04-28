@props([
'hasError' => false,
'hasSuccess' => false,
'textColor' => false,
])

<div {{ $attributes->class(['table-row align-middle cursor-pointer items-center border-b border-gray-200 dark:border-gray-700 dark:bg-gray-700 hover:bg-gray-100', 'text-['.$textColor.']' => $textColor, 'bg-red-300' => $hasError,'bg-green-400' => $hasSuccess]) }}>
    {{ $slot }}

</div>
