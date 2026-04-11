@props([
    'placeholder' => null,
    'showEmpty' => true,
])
@aware(['for'])

<select id="{{ $for }}" {{ $attributes->merge(['class' => 'pr-8 ']) }}>
    @if ($placeholder)
        <option value="">-- {{ $placeholder }} --</option>
    @else
        @if ($showEmpty)
            <option></option>
        @endif
    @endif

    {{ $slot }}
</select>
