<?php

namespace App\Livewire;

use App\Services\LabBuilder;
use App\Services\MicroBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
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

    public $calculatedValues;

    public $micro;

    public $microDateTimeHeaders;

    public $microLabels;

    protected $rules = [
        'input' => 'required',
    ];

    protected $messages = [
        'input.required' => 'No labs were detected.',
    ];

    public function mount(): void
    {
        if (App::environment('local')) {
            $this->input = file_get_contents(resource_path('test.comprehensive.txt'));
        }
    }

    public function clear(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();
    }

    public function save(): void
    {
        $this->validate();

        // Todo: Create DiagnosticTestDirector
        //  ✅abstract datetimeheaders
        //  ✅get micro labels
        //  ✅micro is its own panel
        //  write micro panel after unrecognizedlabs rows
        //  can further refactor to create one master collection to loop through with all results, grouped by panels
        //  this can be a director that builds a labCollection and a microCollection through their builders,
        //  then gets labels, dattimeheaders, etc for all diagnostic tests and merges them
        //  for micro results, display tooltip with result; don't highlight positive or negative

        $userSortDescending = true;

        $microBuilder = new MicroBuilder($this->input);

        $microBuilder->build();

        $this->micro = $microBuilder->getMicroCollection();
        $this->microDateTimeHeaders = $microBuilder->getDateTimeHeaders();
        $this->microLabels = $microBuilder->getMicroLabels();

        //$labBuilder = new LabBuilder(file_get_contents(resource_path('lab.test.txt')));
        $labBuilder = new LabBuilder($this->input);
        //            $labDirector = new DiagnosticTestDirector($labBuilder);
        $labBuilder->build();
        $labBuilder->sort($userSortDescending);

        $this->labs = $labBuilder->getLabCollection();
        $this->unparsableRows = $labBuilder->getUnparsableRows();
        $this->labLabelsSorted = $labBuilder->getLabLabels();
        $this->datetimeHeaders = $labBuilder->getDateTimeHeaders();
        $this->panels = $labBuilder->getPanels();
        $this->calculatedValues = $labBuilder->getCalculatedValues()->map->toArray();

        $this->dispatch('results-ready');

    }

    public function render(): View
    {
        return view('livewire.labs');
    }
}
