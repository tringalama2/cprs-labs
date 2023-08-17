<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Labs extends Component
{
    public $input = '';

    public $output = '';

    public function save(): void
    {
        dd($this->input);
        $this->output = 'this is your input: '.$this->input;
    }

    public function render(): View
    {
        return view('livewire.labs');
    }
}
