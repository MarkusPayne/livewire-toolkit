<div class="flex items-center">

        <span class="text-gray-500 sm:text-sm sm:leading-5">
            $
        </span>


    <div class=" grow ">
        <input
                id="{{ $for }}"
                type="{{ $type }}"
                autocomplete="off"
                {{ $attributes->merge(['class' => 'flex-1 transition duration-150 ease-in-out dark:bg-gray-700 dark:text-gray-100']) }} />
    </div>


</div>
