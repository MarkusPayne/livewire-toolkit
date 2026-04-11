@props([
    'placeholder' => 'Select...',
    'options' => [],
])

<div class="relative w-full" x-data="multiselect(@js($options))" x-modelable="current" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false" {{ $attributes }}>
    <div
        class="flex min-h-10 cursor-pointer items-center rounded-md border border-gray-300"
        x-on:click="toggle()"
        x-on:keydown.enter.prevent="toggle()"
        x-on:keydown.space.prevent="toggle()"
        x-on:keydown.arrow-down.prevent="openAndFocus()"
        tabindex="0"
        role="combobox"
        aria-haspopup="listbox"
        :aria-expanded="open">
        <div class="flex flex-1 flex-wrap gap-1 px-2 py-1">
            <template x-if="current.length === 0">
                <span class="text-sm text-gray-400" x-text="placeholder"></span>
            </template>

            <template x-for="selectedId in current" :key="selectedId">
                <span class="inline-flex items-center gap-1 rounded-md border border-gray-300 py-0.5 pr-1 pl-2 text-sm">
                    <span x-text="options[selectedId]"></span>
                    <button type="button" class="cursor-pointer text-red-500 hover:text-red-700" x-on:click.stop="remove(selectedId)" :aria-label="'Remove ' + options[selectedId]">&times;</button>
                </span>
            </template>
        </div>

        <div class="flex items-center px-2">
            <svg x-show="!open" class="size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="open" x-cloak class="size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </div>
    </div>

    <ul
        x-show="open"
        x-cloak
        x-transition.origin.top
        class="absolute z-50 mt-1 max-h-64 w-full overflow-y-auto rounded-md border border-gray-300 bg-white shadow-sm"
        role="listbox"
        aria-multiselectable="true"
        x-on:keydown.arrow-down.prevent="focusNext()"
        x-on:keydown.arrow-up.prevent="focusPrev()"
        x-on:keydown.enter.prevent="selectFocused()"
        x-on:keydown.space.prevent="selectFocused()"
        x-on:keydown.escape.prevent="open = false"
        tabindex="-1"
        x-ref="listbox">
        <template x-for="(label, id) in options" :key="id">
            <li
                class="cursor-pointer px-3 py-2 text-sm"
                :class="{
                    'bg-green-100': isSelected(id),
                    'bg-gray-100': focusedId === id && !isSelected(id),
                    'bg-green-200': focusedId === id && isSelected(id),
                }"
                role="option"
                :aria-selected="isSelected(id)"
                x-on:mousedown.prevent="select(id)"
                x-on:mouseenter="focusedId = id"
                x-text="label"></li>
        </template>
    </ul>
</div>

@script
    <script>
        Alpine.data('multiselect', (initialOptions = {}) => ({
            open: false,
            options: initialOptions,
            current: [],
            focusedId: null,
            placeholder: '',

            optionIds() {
                return Object.keys(this.options);
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.$nextTick(() => this.$refs.listbox?.focus());
                }
            },

            openAndFocus() {
                this.open = true;
                const ids = this.optionIds();
                if (ids.length > 0) {
                    this.focusedId = ids[0];
                }
                this.$nextTick(() => this.$refs.listbox?.focus());
            },

            isSelected(id) {
                return this.current.includes(String(id));
            },

            select(id) {
                const stringId = String(id);
                if (this.isSelected(stringId)) {
                    this.remove(stringId);
                } else {
                    this.current.push(stringId);
                }
            },

            remove(id) {
                const stringId = String(id);
                this.current = this.current.filter((item) => item !== stringId);
            },

            selectFocused() {
                if (this.focusedId !== null) {
                    this.select(this.focusedId);
                }
            },

            focusNext() {
                const ids = this.optionIds();
                if (ids.length === 0) return;

                const currentIndex = ids.indexOf(String(this.focusedId));
                this.focusedId = ids[Math.min(currentIndex + 1, ids.length - 1)];
            },

            focusPrev() {
                const ids = this.optionIds();
                if (ids.length === 0) return;

                const currentIndex = ids.indexOf(String(this.focusedId));
                this.focusedId = ids[Math.max(currentIndex - 1, 0)];
            },
        }));
    </script>
@endscript
