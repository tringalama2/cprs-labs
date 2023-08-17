<div>
    <form wire:submit.prevent="save">
        <textarea wire:model="input"></textarea>

        <button>Format</button>
    </form>
    <div>
        {{ $output }}
    </div>
</div>
