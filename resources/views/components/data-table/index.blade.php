@props([
    'sortDir',
    'sortBy',

])
<div class="overflow-visible bg-white dark:bg-gray-800" >
    <div class="flex items-center justify-between pb-2">
        <div>
            <x-toolkit::input.select wire:model.change.live="perPage">
                @foreach ($this->perPageOptions as $option)
                    <option value="{{ $option }}" wire:key="datatable-cnt-{{ $option }}">{{ $option }}</option>
                @endforeach
            </x-toolkit::input.select>

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
