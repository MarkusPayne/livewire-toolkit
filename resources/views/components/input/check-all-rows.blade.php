<div x-data="checkAll">
    <x-toolkit::input.checkbox x-ref="checkbox" @change="handleCheck" />
</div>

@script
<script>
    Alpine.data('checkAll', () => {
        return {
            init() {

                this.$watch('$wire.selectedRows', () => {
                    if (this.$wire.selectedRows.length === 0) {
                        this.$refs.checkbox.checked = false;
                    }
                });
            },

            handleCheck(e) {
                e.target.checked ? this.selectAll() : this.deselectAll();
            },

            selectAll() {


                document.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
                    if (checkbox.dataset.selectAll) {
                        checkbox.checked = true;
                        this.$wire.selectedRows.push(checkbox.value);
                    }
                });

            },

            deselectAll() {
                this.$wire.selectedRows = [];
            },
        };
    });
</script>
@endscript
