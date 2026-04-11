<div>
    <div
        x-cloak
        x-data="{ value: true }"
        x-modelable="value"
        {{ $attributes->class(['flex min-w-0 items-center gap-4']) }}
        x-id="['toggle-label']"
    >


        <!-- Label -->
        <label
            @click="$refs.toggle.click(); $refs.toggle.focus()"
            :id="$id('toggle-label')"
            class="select-none text-sm font-medium text-gray-600"
        >
            {{ $slot }}
        </label>

        <!-- Button -->
        <button
            x-ref="toggle"
            @click="value = ! value"
            type="button"
            role="switch"
            :aria-checked="value"
            :aria-labelledby="$id('toggle-label')"
            :class="value ? 'bg-primary-600' : 'bg-gray-800/20'"
            class="relative inline-flex h-4 w-8 items-center rounded-full outline-offset-2 transition"
        >
        <span
            :class="value ? 'translate-x-[15px]' : 'translate-x-[3px]'"
            class="size-3.5 rounded-full bg-white shadow-md transition"
            aria-hidden="true"
        ></span>
        </button>
    </div>
</div>
