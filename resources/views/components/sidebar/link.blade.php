@props([
    'href',
    'icon' => null,
    'iconStyle' => null,
])
<li
        x-data="{
        active: false,
        get hasActiveChild() {
            return ! ! this.$el.querySelector('[data-current]')
        },
        init() {
            if (this.hasActiveChild) this.active = true
        },
    }"
        x-on:livewire:navigated.window="if (hasActiveChild) active = true"
        {{ $attributes->merge(['class' => 'flex items-center gap-x-2 px-2 text-sm hover:text-primary-500 dark:hover:text-white']) }}
>
    <span class="flex items-center" :class="active && 'text-primary-600! dark:text-gray-200!'">
           @if ($icon)
            <span class="flex items-center" :class="active && 'text-sky-700'">
            {{ $icon }}
        </span>
        @endif
    </span>
    <a href="{{ $href }}" wire:navigate class="flex items-center px-3 py-2 leading-5 dark:text-gray-200 text-gray-600 hover:text-primary-500 dark:hover:text-white" :class="active && 'text-primary-600! dark:text-gray-200!'">
        {{ $slot }}
    </a>
</li>
