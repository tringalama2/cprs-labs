<?php

namespace App\Http\Livewire;

use App\Services\LabBuilder;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Labs extends Component
{
    public $input = '';

    public $labs;

    public $labLabelsSorted;

    public $datetimeHeaders;

    public $unparsableRows;

    public $panels;

    public $unrecognizedLabLabels;

    protected $rules = [
        'input' => 'required',
    ];

    protected $messages = [
        'input.required' => 'No labs were detected.',
    ];

    public function clear(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();
    }

    public function save(): void
    {
        $this->validate();

        $userSortDescending = true;

        // Todo: move to new DiagnosticTestDirector

        //$labBuilder = new LabBuilder(file_get_contents(resource_path('lab.test.txt')));
        $labBuilder = new LabBuilder($this->input);
        //            $labDirector = new DiagnosticTestDirector($labBuilder);
        $labBuilder->process();
        $labBuilder->sort($userSortDescending);

        $this->labs = $labBuilder->getLabCollection();
        $this->unparsableRows = $labBuilder->getUnparsableRowsCollection();
        $this->unrecognizedLabLabels = $labBuilder->getUnrecognizedLabLabels();
        $this->labLabelsSorted = $labBuilder->getLabLabels();
        $this->datetimeHeaders = $labBuilder->getDateTimeHeaders();
        $this->panels = $labBuilder->getPanels();

        $this->emit('resultsReady');

    }

    public function render(): View
    {
        return view('livewire.labs');
    }
}
