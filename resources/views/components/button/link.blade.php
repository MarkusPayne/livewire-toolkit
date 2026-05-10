<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'text-gray-700 text-sm leading-5 font-medium focus:outline-hidden focus:text-gray-800 focus:underline
    dark:text-slate-200 dark:focus:text-slate-100
    transition duration-150 ease-in-out' . ($attributes->get('disabled') ? ' opacity-75 cursor-not-allowed' : ''),
    ]) }}
    >
    {{ $slot }}
</button>
