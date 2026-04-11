@props([
    'error' => false,
])
<div class="rounded-lg border-2 border-dashed p-4">
    <div
        x-data="{ isDragging: false, isUploading: false, progress: 0 }"
        x-on:livewire-upload-start="isUploading = true"
        x-on:livewire-upload-finish="isUploading = false"
        x-on:livewire-upload-error="isUploading = false"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        x-on:dragover.prevent="isDragging = true"
        x-on:dragleave="isDragging = false"
        x-on:drop.prevent="
        isDragging = false;
        if (event.dataTransfer.files.length > 0) {
            const files = event.dataTransfer.files;
            @this.uploadMultiple('newUploads', files,
                (uploadedFilename) => {}, () => {}, (event) => {}
            )
        }
        "
        class="w-ful relative flex h-40 grow items-center justify-center rounded-lg border-2 border-gray-300 bg-gray-100 transition-colors duration-300"
        :class="isDragging ? 'border-blue-500 bg-blue-100' : ''">
        <div class="text-center">
            <p class="mb-2 text-gray-600">Drag and drop your file here</p>
            <p class="pb-2 text-sm text-gray-500">Or</p>

            <button type="button" @click="$refs.fileInput.click()" class="mx-auto inline-flex items-center rounded-md border border-transparent bg-sky-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 cursor-pointer">Browse</button>
            <div x-show="isUploading">
                <progress max="100" x-bind:value="progress"></progress>
            </div>
            <input type="file" id="fileInput" x-ref="fileInput" class="sr-only" hidden {{ $attributes->merge(['class' => 'sr-only ']) }} />
            <x-toolkit::input.error :error="$error" />
        </div>
    </div>
</div>
