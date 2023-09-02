<?php

namespace App\Http\Livewire;

use App\Models\Lab;
use Livewire\Component;

class LabSort extends Component
{
    public $sortableId;

    protected $listeners = ['labSortUpdated' => 'saveSort'];

    public function saveSort(array $labSortArray)
    {
        collect($labSortArray)->each(function (string $id, int $index) {
            $saved = Lab::where('id', $id)->update(['order_column' => $index + 1]);
            //dd($saved, $id, $index + 1);
        });

        $this->dispatchBrowserEvent('flash-message', [
            'style' => 'info',
            'message' => 'Lab sort order saved.',
        ]);
    }

    public function render()
    {
        return view('livewire.lab-sort');
    }
}
