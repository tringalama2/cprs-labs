<?php

namespace App\Livewire;

use App\Models\Micro;
use Livewire\Component;

class MicroSort extends Component
{
    public $sortableId;

    protected $listeners = ['microSortUpdated' => 'saveSort'];

    public function saveSort($sort)
    {
        collect($sort)->each(function (string $id, int $index) {
            Micro::where('id', $id)->update(['order_column' => $index + 1]);
        });

        $this->dispatch('flash-message', style: 'info', message: 'Micro sort order saved.');
    }

    public function render()
    {
        return view('livewire.micro-sort');
    }
}
