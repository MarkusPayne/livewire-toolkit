<div {{ $attributes->merge([ 'class' => 'overflow-y-visible bg-white dark:bg-gray-800'])}}>
    <div class="table w-full border-collapse dark:bg-gray-700">
        {{$slot}}
    </div>
</div>
