<?php

namespace App\Http\Livewire;

use App\Models\Panel;
use Livewire\Component;

class PanelSort extends Component
{
    public $sortableId;

    protected $listeners = ['sortUpdated' => 'saveSort'];

    public function saveSort(array $panelSortArray)
    {
        collect($panelSortArray)->each(function (string $id, int $index) {
            Panel::where('id', $id)->update(['order_column' => $index + 1]);
        });

        $this->dispatchBrowserEvent('flash-message', [
            'style' => 'info',
            'message' => 'Panel sort order saved.',
        ]);
    }

    public function render()
    {
        return view('livewire.panel-sort');
    }
}
