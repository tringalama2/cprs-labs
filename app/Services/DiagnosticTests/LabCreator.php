<?php

namespace App\Services\DiagnosticTests;

use App\Services\Parser\LabMetaRowParser;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Str;

class LabCreator implements DiagnosticTestCreatorInterface
{
    public function __construct(public array $diagnosticTests, public int $index)
    {
    }

    public function getDiagnosticTest(): DiagnosticTestInterface
    {
        $metaData = $this->getResultMetaData($this->index);
        $resultData = $this->getResultData($this->index);

        if (count($resultData) < 4) {
            return new UnparsableDiagnosticTest($resultData);
        }

        // todo: create TestNotPerformedDiagnosticTest Type and pass in metadata and lab name
        // Report Released Date/Time:
        //Provider: IQBAL,HUMZAH A
        //  Specimen: PLASMA.           CH 0805 142
        //    Specimen Collection Date: Aug 05, 2023
        //      Test name                Result    units      Ref.   range   Site Code
        //AMMONIA,BLOOD              Test Not Performed

        return new Lab(
            $name = $resultData['name'],
            $result = $resultData['result'],
            $collectionDate = $metaData['collection_date'],
            $releasedDate = $metaData['released_date'],
            $flag = $resultData['flag'],
            $units = $resultData['units'],
            $referenceRange = $resultData['reference_range'],
            $specimen = $metaData['specimen'],
            $orderingProvider = $metaData['ordering_provider'],
            $siteCode = $resultData['site_code'],
        );
    }

    private function getResultMetaData($index): array
    {
        $metaRowParser = new LabMetaRowParser();
        //  from result row, traverse back up to the first metadata headers
        $i = $index;
        while ($index > 0) {
            if (Row::isCollectedDate($this->diagnosticTests[$index])) {
                break;
            }
            $index--;
        }

        return [
            'collection_date' => $metaRowParser->stripDateFromRow($this->diagnosticTests[$index]),
            'specimen' => $metaRowParser->stripSpecimenFromRow($this->diagnosticTests[$index - 1]),
            'ordering_provider' => $metaRowParser->stripProviderFromRow($this->diagnosticTests[$index - 2]),
            'released_date' => $metaRowParser->stripDateFromRow($this->diagnosticTests[$index - 3]),
        ];
    }

    private function getResultData($index): array
    {
        $result_array = Str::of($this->diagnosticTests[$index])->split('/(\s){2,}/')->flatten()->toArray();

        //        if (Str::startsWith($this->diagnosticTests[$index], 'FIO2')) {
        //            dd($this->diagnosticTests[$index], Str::of($this->diagnosticTests[$index])->split('/(\s){2,}/')->flatten()->toArray());
        //        }

        //tests with units
        if (count($result_array) == 5) {
            return [
                'name' => $result_array[0],
                'result' => $result_array[1],
                'flag' => $this->stripFlagFromResult($result_array[1]),
                'units' => $result_array[2],
                'reference_range' => $result_array[3],
                'site_code' => $result_array[4],
            ];
        }

        // tests without units
        if (count($result_array) == 4) {
            return [
                'name' => $result_array[0],
                'result' => $result_array[1],
                'flag' => $this->stripFlagFromResult($result_array[1]),
                'units' => '',
                'reference_range' => $result_array[2],
                'site_code' => $result_array[3],
            ];
        }

        // special tests
        switch ($result_array[0]) {
            case 'FIO2':
            case 'VZ DNA':
                return [
                    'name' => $result_array[0],
                    'result' => $result_array[1],
                    'flag' => '',
                    'units' => '',
                    'reference_range' => '',
                    'site_code' => $result_array[2],
                ];
            case 'C. DIFF TOX B GENE PCR,stoolNegative':
                return [
                    'name' => Str::substr($result_array[0], 0, 28),
                    'result' => Str::substr($result_array[0], -8),
                    'flag' => '',
                    'units' => '',
                    'reference_range' => $result_array[1],
                    'site_code' => $result_array[2],
                ];

        }

        return $result_array;
    }

    public function stripFlagFromResult(string $result): string
    {
        return Str::of($result)->match('/([H|L]\**)/');
    }
}
