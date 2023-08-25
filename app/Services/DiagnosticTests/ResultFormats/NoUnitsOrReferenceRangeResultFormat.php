<?php

namespace App\Services\DiagnosticTests\ResultFormats;

use Illuminate\Support\Str;

class NoUnitsOrReferenceRangeResultFormat implements ResultFormatContract
{
    use WithRows;

    public function __construct(public array $resultPieces)
    {
    }

    public function match(): bool
    {
        return Str::startsWith($this->resultPieces[0], [
            'VZ DNA',
            'FIO2',
            'HSV',
        ]);
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
