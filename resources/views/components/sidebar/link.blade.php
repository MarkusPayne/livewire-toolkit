@props([
    'href',
    'icon' => null
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
        class="flex items-center gap-x-2 px-4 text-sm hover:text-sky-500">
    @if ($icon)
        <span class="flex items-center" :class="active && 'text-sky-700'">
            {{ $icon }}
        </span>
    @endif
    <a href="{{ $href }}" wire:navigate class="flex items-center px-3 py-2 leading-5 text-gray-600 hover:text-sky-500">
        {{ $slot }}
    </a>
</li>
