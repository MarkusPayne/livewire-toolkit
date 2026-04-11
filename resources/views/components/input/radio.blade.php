@props([
    'disabled' => false,
    'readonly' => false,

    'label' => null,
])
<div class="flex items-center pl-3">
    <input {{ $disabled ? 'disabled' : '' }} {{ $readonly ? 'readonly' : '' }} {{ $attributes }} type="radio"
        class="block py-2 transition duration-150 ease-in-out border-gray-300 focus:ring-0" /> <label class="pl-1"><span>{{ $label }}</span></label>
</div>
