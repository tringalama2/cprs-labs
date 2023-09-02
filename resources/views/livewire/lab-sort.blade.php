<div>
    @push('endScripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                let el = document.getElementById('{{ $sortableId }}');
                Sortable.create(el, {
                    onEnd: function () {
                        Livewire.emit('labSortUpdated', this.toArray())
                    }
                });
            });
        </script>
    @endpush()
</div>
