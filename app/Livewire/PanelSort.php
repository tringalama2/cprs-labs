<?php

namespace App\Livewire;

use App\Models\Panel;
use Livewire\Component;

class PanelSort extends Component
{
    public $sortableId;

    protected $listeners = ['panelSortUpdated' => 'saveSort'];

    public function saveSort($sort)
    {
        collect($sort)->each(function (string $id, int $index) {
            Panel::where('id', $id)->update(['order_column' => $index + 1]);
        });

        $this->dispatch('flash-message', style: 'info', message: 'Panel sort order saved.');
    }

    public function render()
    {
        return view('livewire.panel-sort');
    }
}
