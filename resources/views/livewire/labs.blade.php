<div>
    <form wire:submit.prevent="save">
        <textarea wire:model="input"
                  rows="8"
                  class="mb-3 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                  focus:ring-sky-500 focus:border-sky-500"
                  placeholder="Paste labs here..."></textarea>

        <button
            class="focus:outline-none text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300
            font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
            Format
        </button>
    </form>
    <div>
        {{ $output }}
    </div>
</div>
