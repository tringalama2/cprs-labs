<?php

namespace App\Services\DiagnosticTests\ResultFormats;

class FullResultFormat implements ResultFormatContract
{
    use WithRows;

    public function __construct(public array $resultPieces)
    {
    }

    public function match(): bool
    {
        return count($this->resultPieces) == 5;
    }

    public function getResultPieces(): array
    {
        return [
            'name' => $this->resultPieces[0],
            'result' => $this->resultPieces[1],
            'flag' => $this->stripFlagFromResult($this->resultPieces[1]),
            'units' => $this->resultPieces[2],
            'reference_range' => $this->resultPieces[3],
            'site_code' => $this->resultPieces[4],
        ];
    }
}
