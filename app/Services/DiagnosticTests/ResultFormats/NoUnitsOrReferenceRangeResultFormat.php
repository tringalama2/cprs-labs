<?php

namespace App\Services\DiagnosticTests\ResultFormats;

class NoUnitsOrReferenceRangeResultFormat implements ResultFormatContract
{
    use WithRows;

    public function __construct(public array $resultPieces)
    {
    }

    public function match(): bool
    {
        return count($this->resultPieces) == 3;
    }

    public function getResultPieces(): array
    {
        return [
            'name' => $this->resultPieces[0],
            'result' => $this->resultPieces[1],
            'flag' => $this->stripFlagFromResult($this->resultPieces[1]),
            'units' => '',
            'reference_range' => '',
            'site_code' => $this->resultPieces[2],
        ];
    }
}
