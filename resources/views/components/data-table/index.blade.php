@props([
    'sortDir',
    'sortBy',
    'showExport' => false,
])
<div class="overflow-visible bg-white dark:bg-gray-800" x-data="{ showExport: @js($showExport) }">
    <div class="flex items-center justify-between pb-2">
        <div>
            <select wire:model.change.live="perPage"
                    class="border-gray-300 rounded-md shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                @foreach ($this->perPageOptions as $option)
                    <option value="{{ $option }}" wire:key="datatable-cnt-{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center justify-between gap-x-4 pb-3">
            {{ $extraHeading ?? null }}

            <div x-show="showExport">
                <svg class="inline-block h-5 w-5 cursor-pointer text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
            </div>
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
