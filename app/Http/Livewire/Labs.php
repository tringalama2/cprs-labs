<?php

namespace App\Http\Livewire;

use App\Exceptions\LabBuilderEmptyCollectionException;
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

    public function clear(): void
    {
        $this->reset();
    }

    public function save(): void
    {
        $userSortDescending = true;

        // Todo: move to new DiagnosticTestDirector
        try {

            //$labBuilder = new LabBuilder(file_get_contents(resource_path('lab.test.txt')));
            $labBuilder = new LabBuilder($this->input);
            $labBuilder->process();
            $labBuilder->sort($userSortDescending);

            $this->labs = $labBuilder->getLabCollection();
            $this->unparsableRows = $labBuilder->getUnparsableRowsCollection();
            $this->unrecognizedLabLabels = $labBuilder->getUnrecognizedLabLabels();
            $this->labLabelsSorted = $labBuilder->getLabLabels();
            $this->datetimeHeaders = $labBuilder->getCollectionDateHeaders();
            $this->panels = $this->labLabelsSorted->groupBy('panel')->map->count();
            $labBuilder->logUnmatchedLabs();

            $this->emit('resultsReady');

        } catch (LabBuilderEmptyCollectionException $exception) {
            report($exception);
            //dd('Lab Collection Empty.  Consider a director class to ensure the order of the builder?');

            //            return back()->withError($exception->getMessage())->withInput();
        }
    }

    public function render(): View
    {
        return view('livewire.labs');
    }
}
