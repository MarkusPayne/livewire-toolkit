<form {{ $attributes->merge(['class' => 'md:grid  md:gap-6 md:grid-cols-12 items-start']) }}>
    {{ $slot }}
</form>
