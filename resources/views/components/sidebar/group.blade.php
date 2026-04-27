@props([
    'label',
])

<div
    x-data="{
        open: false,
        get hasActiveChild() {
            return ! ! this.$el.querySelector('[data-current]')
        },
        init() {
            if (this.hasActiveChild) this.open = true
        },
    }"
    x-on:livewire:navigated.window="if (hasActiveChild) open = true"
    {{ $attributes->merge(['class' => '']) }}>
    <div @click="open = !open" class="flex w-full cursor-pointer items-center justify-between rounded-lg bg-white px-4 py-2" :class="hasActiveChild && 'bg-gray-100 font-semibold'">
        <span class="flex items-center gap-x-2" :class="open && 'font-semibold!'">
            {{ $icon ?? '' }}

            <span class="px-3 leading-5">{{ $label }}</span>
        </span>
        <svg class="size-5 transition-transform" :class="open && 'rotate-90 font-semibold!'" viewBox="0 0 20 20" fill="currentColor">
            <path
                fill-rule="evenodd"
                d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.08-.02Z"
                clip-rule="evenodd" />
        </svg>
    </div>
    <div x-show="open" x-collapse x-cloak class="ml-5">
        {{ $slot }}
    </div>
</div>
