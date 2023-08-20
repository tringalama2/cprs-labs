<?php

namespace App\Http\Controllers;

use App\Services\LabBuilder;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TestController extends Controller
{
    private $processor;

    public function __invoke()
    {
        //  https://regex101.com/r/dF3aE6/1

        $labBuilder = new LabBuilder(file_get_contents(resource_path('labs.short.test.txt')));
        $labBuilder->process();

        $labs = $labBuilder->getLabCollection();
        $unparsableRows = $labBuilder->getUnparsableRowsCollection();

        $labs = $labs->sortByDesc(function (Collection $row, int $key) {
            return $row['collection_date']->toDateTimeString();
        });

        $labLabelsSorted = $labs->pluck('name')->unique()->sortBy(function (?string $name, int $key) {
            $order = array_search($name, include(app_path('Services/Format/sort.php')));
            if ($order === false) {
                return 10000;
            }

            return $order;
        });

        $datetimeHeader = $labs->pluck('collection_date')->unique()->map(function ($item) {
            return Carbon::parse($item)->format('n/j/y<b\r>G:i');
        });

        return view('test', compact('labs', 'labLabelsSorted', 'datetimeHeader', 'unparsableRows'));
    }
}
