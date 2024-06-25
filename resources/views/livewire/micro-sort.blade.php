<div>
    @push('endScripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                let el = document.getElementById('{{ $sortableId }}');
                Sortable.create(el, {
                    onEnd: function () {
                        Livewire.dispatch('microSortUpdated', {sort: this.toArray()})
                    }
                });
            });
        </script>
    @endpush()
</div>
