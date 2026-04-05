@props([
    'grow' => true,
])
<div {{ $attributes->merge(['class' => 'items-center align-middle table-cell p-4 px-2 py-3 text-sm text-gray-600 whitespace-no-wrap dark:text-gray-200']) }}>
    <div @class(['grow' => $grow])>{{ $slot }}</div>
</div>
