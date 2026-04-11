@props([
    'error' => false,
    'inline' => false,
])


@if ($error)
    <div x-init="$focus.focus($refs.errors)" @class([
        'mt-1 text-sm text-red-600 is-invalid',
        'text-right' => $inline,

    ]) x-ref="errors">{{ $error }}</div>
@endif
