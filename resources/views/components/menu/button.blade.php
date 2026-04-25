<button type="button" x-menu:button {{ $attributes->merge([ 'class' => 'cursor-pointer focus:outline-hidden'])}} >
    {{ $slot }}
</button>
