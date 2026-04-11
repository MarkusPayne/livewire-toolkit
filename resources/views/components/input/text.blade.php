@props([
    'type' => 'text',
])
@aware(['for'])

<div class="flex grow items-center">
    <input
        id="{{ $for }}"
        type="{{ $type }}"
        autocomplete="off"
        {{ $attributes->merge(['class' => 'flex-1 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-gray-100']) }} />
</div>
