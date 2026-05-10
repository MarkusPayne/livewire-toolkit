<x-toolkit::button {{ $attributes->merge(['class' => 'text-white bg-red-600 hover:bg-red-500 active:bg-red-600 border-red-600 dark:bg-rose-500 dark:hover:bg-rose-400 dark:active:bg-rose-500 dark:border-rose-500'])
    }}>{{ $slot }}</x-toolkit::button>
