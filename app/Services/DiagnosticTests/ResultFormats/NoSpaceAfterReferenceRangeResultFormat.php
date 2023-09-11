<?php

namespace App\Services\DiagnosticTests\ResultFormats;

use Illuminate\Support\Str;

class NoSpaceAfterReferenceRangeResultFormat implements ResultFormatContract
{
    use WithRows;

    private string $name;

    public function __construct(public array $resultPieces)
    {
    }

    public function getResultPieces(): array
    {
        return [
            'name' => $this->resultPieces[0],
            'result' => $this->resultPieces[1],
            'flag' => $this->stripFlagFromResult($this->resultPieces[1]),
            'units' => '',
            'reference_range' => Str::of($this->resultPieces[2])->before('[')->trim(),
            'site_code' => Str::of($this->resultPieces[2])->match('/\[([0-9]+?)\]$/'),
        ];
    }

    public function match(): bool
    {
        return Str::startsWith($this->resultPieces[0], [
            'HBV CORE AB TOTAL,blood',
            'RPR,blood',
        ]);
    }
}
