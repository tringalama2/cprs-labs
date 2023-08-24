<?php

namespace App\Http\Controllers;

use App\Services\LabBuilder;

class TestController extends Controller
{
    private $processor;

    public function __invoke()
    {
        //  https://regex101.com/r/dF3aE6/1

        $userSortDescending = true;

        $labBuilder = new LabBuilder(file_get_contents(resource_path('lab2.test.txt')));

        $labBuilder->process();
        $labBuilder->sort($userSortDescending);

        $labs = $labBuilder->getLabCollection();
        $unparsableRows = $labBuilder->getUnparsableRows();
        $unrecognizedLabLabels = $labBuilder->getUnrecognizedLabLabels();
        $labLabelsSorted = $labBuilder->getLabLabels();
        $datetimeHeaders = $labBuilder->getDateTimeHeaders();
        $panels = $labLabelsSorted->groupBy('panel')->map->count();

        return view('test',
            compact('labs', 'labLabelsSorted', 'datetimeHeaders', 'unparsableRows', 'panels', 'unrecognizedLabLabels'));
    }
}
