<?php

namespace App\Services\DiagnosticTests\ResultFormats;

use Illuminate\Support\Str;

class NoSpaceAfterNameResultFormat implements ResultFormatContract
{
    use WithRows;

    private string $name;

    public function __construct(public array $resultPieces)
    {
    }

    public function match(): bool
    {
        return Str::startsWith($this->resultPieces[0], [
            'MRSA SURVL NARES AGAR,E-SWAB',
            'MRSA SURVL NARES DNA,E-SWAB',
            'C. DIFF TOX B GENE PCR,stool',
            'OCCULT BLOOD RANDOM-GUAIAC ',
        ]);
    }

    public function getResultPieces(): array
    {
        $result = Str::substr($this->resultPieces[0],
            (strlen($this->resultPieces[0]) - strlen($this->name)) * -1);
        $pieceCount = count($this->resultPieces);

        return [
            'name' => trim($this->name),
            'result' => $result,
            'flag' => $this->stripFlagFromResult($result),
            // check if units are avail based on number of pieces
            'units' => $this->resultPieces[$pieceCount - 3] === $this->resultPieces[0] ? '' : $this->resultPieces[$pieceCount - 3],
            'reference_range' => $this->resultPieces[$pieceCount - 2],
            'site_code' => $this->resultPieces[$pieceCount - 1],
        ];
    }
}
