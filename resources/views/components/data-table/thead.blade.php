<div class="table-header-group [&_.table-row:hover]:bg-transparent!" x-data="{
    sortBy: false,
    sortDir: false,
    get isAsc() {
        return this.sortDir == 'ASC'
    },
}">
    <div x-modelable="sortBy" wire:model.change.live="sortBy"></div>
    <div x-modelable="sortDir" wire:model.change.live="sortDir"></div>
    {{ $slot }}
</div>
