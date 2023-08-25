<?php

namespace App\Services\DiagnosticTests\ResultFormats;

class NoUnitsResultFormat implements ResultFormatContract
{
    use WithRows;

    public function __construct(public array $resultPieces)
    {
    }

    public function match(): bool
    {
        return count($this->resultPieces) == 4;
    }

    public function getResultPieces(): array
    {
        return [
            'name' => $this->resultPieces[0],
            'result' => $this->resultPieces[1],
            'flag' => $this->stripFlagFromResult($this->resultPieces[1]),
            'units' => '',
            'reference_range' => $this->resultPieces[2],
            'site_code' => $this->resultPieces[3],
        ];
    }
}
