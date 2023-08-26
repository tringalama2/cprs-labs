<?php

namespace App\Services\DiagnosticTests;

use App\Services\DiagnosticTests\ResultFormats\FullResultFormat;
use App\Services\DiagnosticTests\ResultFormats\NoSpaceAfterNameResultFormat;
use App\Services\DiagnosticTests\ResultFormats\NoUnitsOrReferenceRangeResultFormat;
use App\Services\DiagnosticTests\ResultFormats\NoUnitsResultFormat;
use App\Services\DiagnosticTests\ResultFormats\ResultFormatContract;
use App\Services\Parser\LabMetaRowParser;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LabCreator implements DiagnosticTestCreatorInterface
{
    public function __construct(public Collection $diagnosticTests, public int $index)
    {
    }

    public function getDiagnosticTest(): DiagnosticTestInterface
    {
        $metaData = $this->getResultMetaData($this->index);
        $resultPieces = Str::of($this->diagnosticTests->get($this->index))->split('/(\s){2,}/')->flatten()->toArray();

        if (Str::of($resultPieces[1])->contains(['canc'])) {
            return new CancelledDiagnosticTest($resultPieces);
        }

        $resultFormats = [
            NoUnitsOrReferenceRangeResultFormat::class,
            NoSpaceAfterNameResultFormat::class,
            NoUnitsResultFormat::class,
            FullResultFormat::class,
        ];

        $lab = $this->matchAndReturnResult($resultPieces, $metaData, $resultFormats);
        if ($lab) {
            return $lab;
        }
        //        // todo: create TestNotPerformedDiagnosticTest Type and pass in metadata and lab name
        //                         do we need this info?
        //        // Report Released Date/Time:
        //        //Provider: IQBAL,HUMZAH A
        //        //  Specimen: PLASMA.           CH 0805 142
        //        //    Specimen Collection Date: Aug 05, 2023
        //        //      Test name                Result    units      Ref.   range   Site Code
        //        //AMMONIA,BLOOD              Test Not Performed
        //

        return new UnparsableDiagnosticTest($resultPieces);
    }

    private function getResultMetaData($index): array
    {
        $metaRowParser = new LabMetaRowParser();
        //  from result row, traverse back up to the first metadata headers
        $i = $index;
        while ($index > 0) {
            if (Row::isCollectedDate($this->diagnosticTests->get($index))) {
                break;
            }
            $index--;
        }

        return [
            'collection_date' => $metaRowParser->stripDateFromRow($this->diagnosticTests->get($index)),
            'specimen' => $metaRowParser->stripSpecimenFromRow($this->diagnosticTests->get($index - 1)),
            'ordering_provider' => $metaRowParser->stripProviderFromRow($this->diagnosticTests->get($index - 2)),
            'released_date' => $metaRowParser->stripDateFromRow($this->diagnosticTests->get($index - 3)),
        ];
    }

    private function matchAndReturnResult(array $resultPieces, array $metaData, array $resultFormats): Lab|false
    {
        foreach ($resultFormats as $formatClass) {
            $format = new $formatClass($resultPieces);

            if (! ($format instanceof ResultFormatContract)) {
                Log::error('Attempted Row Format in Lab Creator is not an instance of ResultFormatContract');
                abort(500);
            }

            if ($format->match()) {
                return $this->createLabWithMeta($format->getResultPieces(), $metaData);
            }
        }

        return false;
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
