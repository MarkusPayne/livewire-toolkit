@props(['rows' => 3])
@aware(['for'])
<div class="flex grow rounded-md shadow-xs">
    <textarea
        id="{{ $for }}"
        {{ $attributes }}
        rows="{{ $rows }}"
        name="{{ $attributes->wire('model')->value() }}"
        class="flex-1 grow transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
</div>
