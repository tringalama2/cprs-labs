<?php

namespace App\Http\Controllers;

use App\Exceptions\LabBuilderEmptyCollectionException;
use App\Services\LabBuilder;

class TestController extends Controller
{
    private $processor;

    public function __invoke()
    {
        //  https://regex101.com/r/dF3aE6/1

        $userSortDescending = true;

        try {

            $labBuilder = new LabBuilder(file_get_contents(resource_path('lab.test.txt')));
            $labBuilder->process();
            $labBuilder->sort($userSortDescending);

            $labs = $labBuilder->getLabCollection();
            $unparsableRows = $labBuilder->getUnparsableRowsCollection();
            $labLabelsSorted = $labBuilder->getLabLabels();
            $datetimeHeaders = $labBuilder->getCollectionDateHeaders();
            $panels = $labLabelsSorted->groupBy('panel')->map->count();
            //            dd($labLabelsSorted);
            //            dd($labs->groupBy('collection_date')->first()->where('name', 'WBC'));

        } catch (LabBuilderEmptyCollectionException $exception) {
            report($exception);
            //dd('Lab Collection Empty.  Consider a director class to ensure the order of the builder?');

            return back()->withError($exception->getMessage())->withInput();
        }

        return view('test', compact('labs', 'labLabelsSorted', 'datetimeHeaders', 'unparsableRows', 'panels'));
    }
}
