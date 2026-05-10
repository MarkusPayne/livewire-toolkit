<button
    type="button"
    x-menu:item
    x-bind:class="{
        'bg-slate-100 text-gray-900 dark:bg-slate-700 dark:text-slate-50': $menuItem.isActive,
        'text-gray-600 dark:text-slate-400': ! $menuItem.isActive,
        'opacity-50 cursor-not-allowed': $menuItem.isDisabled,
    }"
    class="flex items-center gap-2 w-full px-3 py-2 text-left text-sm hover:bg-slate-50 disabled:text-gray-500 transition-colors cursor-pointer dark:hover:bg-slate-800 dark:disabled:text-slate-500"
    {{ $attributes }}
>
    {{ $icon ?? '' }}
    {{ $slot }}
</button>
