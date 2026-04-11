@props([
    'mode' => 'single',
    'time' => false,
    'month' => false,
])
<div class="flex grow items-center">
    <input
        x-data="date(
                    '{{ $mode }}',
                    {{ $time ? 'true' : 'false' }},
                    {{ $month ? 'true' : 'false' }},
                )"
        x-ref="picker"
        type="text"
        x-modelable="currentDate"
        autocomplete="off"
        {{
            $attributes->merge([
                'class' => 'flex-1 border-gray-300 block w-full transition duration-150 ease-in-out',
            ])
        }} />
</div>

@script
    <script>
        Alpine.data('date', (mode = 'single', enableTime = false, monthOnly = false) => ({
            picker: null,
            currentDate: '',

            init() {
                let dateFormat = 'Y-m-d';
                if (enableTime) dateFormat = 'Y-m-d H:i:S';
                if (monthOnly) dateFormat = 'F d';

                this.picker = flatpickr(this.$refs.picker, {
                    dateFormat,
                    disableMobile: true,
                    mode,
                    enableTime,
                    onClose: (dates, dateString) => {
                        this.currentDate = dateString;
                    },
                });

                this.$refs.picker.addEventListener('click', (e) => e.stopPropagation());
                this.$watch('currentDate', (value) => this.picker.setDate(value));
            },

            destroy() {
                this.picker?.destroy();
            },
        }));
    </script>
@endscript
