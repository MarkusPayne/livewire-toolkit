@props([
    'name' => 'large-modal',
    'title' => null,
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
            :id="$id('large-modal')"
            role="dialog"
            aria-modal="true"
            {{ $attributes }}
            class="fixed inset-0 z-20 overflow-y-auto text-left">
            <div x-transition:enter.opacity class="fixed inset-0 bg-black/50"></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:items-center sm:p-0">
                {{-- overflow-hidden --}}
                <div class="relative my-8 w-full rounded-xs bg-white pt-5 text-left shadow-xl sm:mx-4 sm:max-w-(--breakpoint-2xl) sm:px-4 sm:py-6" x-trap.noscroll.inert="showModal">
                    <div class="absolute top-2 right-2 block pt-2 pr-2">
                        <span type="button" x-on:click="showModal = false" class="cursor-pointer rounded-xs bg-white text-red-500 hover:text-red-700">
                            <span class="sr-only">Close</span>
                            <x-toolkit::close />
                        </span>
                    </div>
                    <div class="overflow-y-auto px-4 pt-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endteleport
