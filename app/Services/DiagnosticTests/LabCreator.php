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

        return $result_array;
    }

    public function stripFlagFromResult(string $result): string
    {
        return Str::of($result)->match('/([H|L]\**)/');
    }
}
