<?php

namespace App\Livewire;

use App\Models\Lab;
use Livewire\Component;

class LabSort extends Component
{
    public $sortableId;

    protected $listeners = ['labSortUpdated' => 'saveSort'];

    public function saveSort($sort)
    {
        collect($sort)->each(function (string $id, int $index) {
            $saved = Lab::where('id', $id)->update(['order_column' => $index + 1]);
            //dd($saved, $id, $index + 1);
        });

        $this->dispatch('flash-message', style: 'info', message: 'Panel sort order saved.');
    }

    public function render()
    {
        return view('livewire.lab-sort');
    }
}
