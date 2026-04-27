@props([
    'sortDir',
    'sortBy',

])
<div class="overflow-visible bg-white dark:bg-gray-800" >
    <div class="flex items-center justify-between pb-2">
        <div>
            <select wire:model.change.live="perPage"
                    class="border-gray-300 rounded-md shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 m-w-20">
                @foreach ($this->perPageOptions as $option)
                    <option value="{{ $option }}" wire:key="datatable-cnt-{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center justify-between gap-x-4 pb-3">
            {{ $extraHeading ?? null }}


        </div>
    </div>
    <div class="table w-full border-collapse dark:bg-gray-700">
        {{ $slot }}
    </div>
    <div class="grid grid-cols-1">
        <div class="col pt-10 dark:bg-gray-800 dark:text-gray-200">
            {{ $this->rows->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
