<div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <span class="text-gray-500 dark:text-slate-400 sm:text-sm sm:leading-5">
            $
        </span>
    </div>
    <input
            type="number"
            increment="1"
            autocomplete="off"
            {{ $attributes->merge(['class' => ' pl-7  w-full duration-150 ease-in-out dark:bg-gray-700 dark:text-gray-100']) }} />
</div>
