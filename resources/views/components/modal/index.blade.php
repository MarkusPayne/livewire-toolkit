@props([
    'name' => 'modal',
    'maxWidth' => 'sm:max-w-4xl',
])
@teleport('body')
    <div>
        <div
            x-data="{ showModal: false, name: '{{ $name }}' }"
            x-modelable="showModal"
            x-show="showModal"
            x-on:open-modal.window="showModal = $event.detail.name === name"
            x-on:close-modal.window="if (!$event.detail?.name || $event.detail.name === name) showModal = false"
            x-cloak
            :id="$id('modal')"
            role="dialog"
            aria-modal="true"
            {{ $attributes }}
            class="fixed inset-0 z-70 overflow-y-auto text-left">
            <div x-transition:enter.opacity class="fixed inset-0 bg-black/80"></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative my-8 w-full rounded-xs bg-white pt-5 text-left shadow-xl sm:mx-4 {{ $maxWidth }} sm:px-4 sm:py-6" x-trap.noscroll.inert="showModal">
                    <div class="flex items-center justify-between px-4 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $title ?? '' }}
                        </h3>
                        <span type="button" x-on:click="showModal = false" class="cursor-pointer rounded-xs bg-white text-red-500 hover:text-red-700">
                            <span class="sr-only">Close</span>
                            <x-toolkit::icon.close />
                        </span>
                    </div>
                    <div class="overflow-y-auto px-4 pt-4">
                        {{ $slot }}
                    </div>
                    @if (isset($footer))
                        <div class="mt-4 flex items-center justify-end gap-x-3 border-t border-gray-200 px-4 pt-4">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endteleport
