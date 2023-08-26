<?php

namespace App\Services\DiagnosticTests;

use App\Services\Parser\MicroParser;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;

class MicroCreator implements DiagnosticTestCreatorInterface
{
    public function __construct(public Collection $diagnosticTests, public int $microHeaderIndex)
    {
    }

    public function getDiagnosticTest(): DiagnosticTestInterface
    {
        $microRows = $this->diagnosticTests
            ->slice($this->microHeaderIndex)
            ->takeUntil(function (string $row, int $index) {
                return Row::isSeparator($row);
            });

        $microRowsAsString = $microRows->implode("\n");

        return new Micro(
            MicroParser::getTestName($microRowsAsString),
            MicroParser::getResult($microRowsAsString),
            MicroParser::getCollectionDate($microRowsAsString),
            MicroParser::getCompletedDate($microRowsAsString),
            $flag = MicroParser::getSample($microRowsAsString),
            $units = MicroParser::getSpecimen($microRowsAsString),
            MicroParser::getProvider($microRowsAsString),
        );
    }
}
