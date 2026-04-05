@props(['sortField' => false])

<div
    x-data="{
        sortField:'{{ $sortField }}',
        toggle() {
            if(this.sortField) {
                if(this.sortBy == this.sortField) {
                    sortDir = sortDir == 'ASC' ? 'DESC' : 'ASC'
                } else {
                    this.sortBy = this.sortField
                    this.sortDir = 'ASC'
                }
            }
        }
    }"
    x-model="sortBy"
    {{ $attributes->merge(['class' => 'table-cell p-4 px-2 py-2 text-sm font-medium text-gray-600 dark:text-gray-200 capitalize']) }}>
    <button class="inline-flex items-center" :class="sortField && 'cursor-pointer'" x-on:click="toggle()">
        {{ $slot }}

        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" :class="(!isAsc) || 'rotate-180'" viewBox="0 0 20 20"
             fill="currentColor" x-show="sortBy == sortField">
            <path fill-rule="evenodd"
                  d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                  clip-rule="evenodd" />
        </svg>
    </button>
</div>
