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
        //        $labDirector = new LabDirector(file_get_contents(resource_path('labs.short.test.txt')));
        //        $labDirector->buildLabs();
        //        $labBuilder = $labDirector->getLabBuilder();

        $labBuilder = new LabBuilder(file_get_contents(resource_path('labs.short.test.txt')));
        $labBuilder->process();
        //        dd($labBuilder->getLabCollection());
        $labs = $labBuilder->getLabCollection();

        //        $labs = collect([
        //
        //            collect([
        //                'name' => 'Hgb',
        //                'result' => '10.2',
        //                'collection_date' => Carbon::now()->subDay()->format('Y-m-d H:i'),
        //            ]),
        //            collect([
        //                'name' => 'PLT',
        //                'result' => '265',
        //                'collection_date' => Carbon::now()->subDay()->format('Y-m-d H:i'),
        //            ]),
        //            collect([
        //                'name' => 'Hgb',
        //                'result' => '10.1',
        //                'collection_date' => Carbon::now()->subMinutes(30)->subHours(2)->format('Y-m-d H:i'),
        //            ]),
        //            collect([
        //                'name' => 'WBC',
        //                'result' => '12.5',
        //                'collection_date' => Carbon::now()->format('Y-m-d H:i'),
        //            ]),
        //            collect([
        //                'name' => 'Hgb',
        //                'result' => '8.9',
        //                'collection_date' => Carbon::now()->format('Y-m-d H:i'),
        //            ]),
        //            collect([
        //                'name' => 'PLT',
        //                'result' => '250',
        //                'collection_date' => Carbon::now()->format('Y-m-d H:i'),
        //            ]),
        //            collect([
        //                'name' => 'WBC',
        //                'result' => '11.0',
        //                'collection_date' => Carbon::now()->subDay()->format('Y-m-d H:i'),
        //            ]),
        //        ]);

        $labs = $labs->sortByDesc(function (Collection $row, int $key) {
            return $row['collection_date']->toDateTimeString();

        });

        $labLabelsSorted = $labs->pluck('name')->unique()->sortBy(function (?string $name, int $key) {
            $order = array_search($name, include(app_path('Services/Format/').'sort.php'));
            if (! $order) {
                return 10000;
            }

            return $order;
        });

        $datetimeHeader = $labs->pluck('collection_date')->unique()->map(function ($item) {
            return Carbon::parse($item)->format('n/j/y<b\r>G:i');
        });

        $unableToParse = $labBuilder->getUnableToParseCollection();

        return view('test', compact('labs', 'labLabelsSorted', 'datetimeHeader', 'unableToParse'));
    }
}
