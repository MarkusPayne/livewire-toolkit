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
                <div class="relative my-5 w-full rounded-xs bg-white text-left shadow-xl sm:mx-4 {{ $maxWidth }} sm:px-4 py-5 dark:bg-slate-900" x-trap.noscroll.inert="showModal">
                    <div class="flex items-center justify-between px-4 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-50">
                            {{ $title ?? '' }}
                        </h3>
                        <span type="button" x-on:click="showModal = false" class="cursor-pointer rounded-xs bg-white text-red-500 hover:text-red-700 dark:bg-slate-900 dark:text-rose-400 dark:hover:text-rose-300">
                            <span class="sr-only">Close</span>
                            <x-toolkit::icon.close />
                        </span>
                    </div>
                    <div class="overflow-y-auto px-4 pt-4">
                        {{ $slot }}
                    </div>
                    @if (isset($footer))
                        <div class="mt-4 flex items-center justify-end gap-x-3 border-t border-gray-200 px-4 pt-4 dark:border-slate-800">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endteleport
