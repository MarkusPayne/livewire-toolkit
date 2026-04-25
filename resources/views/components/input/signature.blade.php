<div x-data="signature" x-modelable="currentSignature" wire:ignore {{ $attributes->merge(['class' => 'min-w-full sm:min-w-lg flex-col']) }}>
    <div class="flex items-center justify-center pb-3">
        <button type="button" class="flex cursor-pointer items-center text-sm text-gray-700" x-on:click="clearSignature()">
            <span class="pr-2">Clear</span>
            <x-toolkit::icon.close />
        </button>
    </div>
    <div x-ref="canvasWrap" class="relative w-full" style="aspect-ratio: 2/1">
        <canvas class="absolute inset-0 h-full w-full border bg-gray-100" x-ref="pad" :id="$id('signature-input')"></canvas>
    </div>
</div>

@script
    <script>
        Alpine.data('signature', () => ({
            signaturePad: null,
            currentSignature: null,
            resizeObserver: null,

            init() {
                this.resizeCanvas();
                this.signaturePad = new SignaturePad(this.$refs.pad);

                this.signaturePad.addEventListener('endStroke', () => {
                    this.currentSignature = this.signaturePad.toDataURL();
                });

                this.resizeObserver = new ResizeObserver(() => this.resizeCanvas());
                this.resizeObserver.observe(this.$refs.canvasWrap);

                this.$watch('currentSignature', (value) => {
                    if (!value) {
                        this.signaturePad.clear();
                    }
                });
            },

            resizeCanvas() {
                const canvas = this.$refs.pad;
                const wrap = this.$refs.canvasWrap;
                const width = Math.floor(wrap.clientWidth);
                const height = Math.floor(wrap.clientHeight);

                if (width === 0 || (canvas.width === width && canvas.height === height)) return;

                canvas.width = width;
                canvas.height = height;

                if (this.signaturePad) {
                    this.signaturePad.clear();
                }
            },

            clearSignature() {
                this.signaturePad.clear();
                this.currentSignature = null;
            },

            destroy() {
                this.resizeObserver?.disconnect();
            },
        }));
    </script>
@endscript
