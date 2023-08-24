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
        $result_array = Str::of($this->diagnosticTests[$this->index])->split('/(\s){2,}/')->flatten()->toArray();

        if (Str::of($result_array[1])->contains(['canc'])) {
            return new CancelledDiagnosticTest($result_array);
        }

        if (Str::startsWith($result_array[0], ['VZ DNA', 'FIO2', 'HSV'])) {
            return $this->createLabWithMeta([
                'name' => $result_array[0],
                'result' => $result_array[1],
                'flag' => $this->stripFlagFromResult($result_array[1]),
                'units' => '',
                'reference_range' => '',
                'site_code' => $result_array[2],
            ], $metaData);
        }

        if (Str::startsWith($result_array[0], [
            'MRSA SURVL NARES AGAR,E-SWAB',
            'C. DIFF TOX B GENE PCR,stool',
        ])) {

            return $this->createLabWithMeta([
                'name' => Str::substr($result_array[0], 0, 28),
                'result' => Str::substr($result_array[0], (strlen($result_array[0]) - 28) * -1),
                'flag' => $this->stripFlagFromResult(Str::substr($result_array[0],
                    (strlen($result_array[0]) - 28) * -1)),
                'units' => '',
                'reference_range' => $result_array[1],
                'site_code' => $result_array[2],
            ], $metaData);
        }

        if (Str::startsWith($result_array[0], [
            'MRSA SURVL NARES DNA,E-SWAB',
            'OCCULT BLOOD RANDOM-GUAIAC ',
        ])) {

            return $this->createLabWithMeta([
                'name' => trim(Str::substr($result_array[0], 0, 27)),
                'result' => Str::substr($result_array[0], (strlen($result_array[0]) - 27) * -1),
                'flag' => $this->stripFlagFromResult(Str::substr($result_array[0],
                    (strlen($result_array[0]) - 28) * -1)),
                'units' => '',
                'reference_range' => $result_array[1],
                'site_code' => $result_array[2],
            ], $metaData);
        }

        // todo: create TestNotPerformedDiagnosticTest Type and pass in metadata and lab name
        // Report Released Date/Time:
        //Provider: IQBAL,HUMZAH A
        //  Specimen: PLASMA.           CH 0805 142
        //    Specimen Collection Date: Aug 05, 2023
        //      Test name                Result    units      Ref.   range   Site Code
        //AMMONIA,BLOOD              Test Not Performed

        //        if (Str::startsWith($this->diagnosticTests[$index], 'FIO2')) {
        //            dd($this->diagnosticTests[$index], Str::of($this->diagnosticTests[$index])->split('/(\s){2,}/')->flatten()->toArray());
        //        }

        if (count($result_array) == 4) {
            return $this->createLabWithMeta([
                'name' => $result_array[0],
                'result' => $result_array[1],
                'flag' => $this->stripFlagFromResult($result_array[1]),
                'units' => '',
                'reference_range' => $result_array[2],
                'site_code' => $result_array[3],
            ], $metaData);
        }

        if (count($result_array) == 5) {
            return $this->createLabWithMeta([
                'name' => $result_array[0],
                'result' => $result_array[1],
                'flag' => $this->stripFlagFromResult($result_array[1]),
                'units' => $result_array[2],
                'reference_range' => $result_array[3],
                'site_code' => $result_array[4],
            ], $metaData);
        }

        return new UnparsableDiagnosticTest($result_array);

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

    private function createLabWithMeta($resultData, $metaData): Lab
    {
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

    public function stripFlagFromResult(string $result): string
    {
        return Str::of($result)->match('/([H|L]\**)$/');
    }
}
