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
        $availableNames = [
            'MRSA SURVL NARES AGAR,E-SWAB',
            'MRSA SURVL NARES DNA,E-SWAB',
            'C. DIFF TOX B GENE PCR,stool',
            'OCCULT BLOOD RANDOM-GUAIAC ',
            'Occult Blood (Fit) #1 Of 1 ',
            'NORHYDROCODONE QUANT,urine ',
            'Heparin Induced Plt Ab,Blood',
        ];

        foreach ($availableNames as $item) {
            if (Str::startsWith($this->resultPieces[0], $item)) {
                $this->name = Str::of($item)->trim();

                return true;
            }
        }

        return false;
    }

    public function getResultPieces(): array
    {
        $result = Str::of($this->resultPieces[0])->after($this->name)->trim();

        $pieceCount = count($this->resultPieces);

        return [
            'name' => $this->name,
            'result' => $result,
            'flag' => $this->stripFlagFromResult($result),
            // check if units are avail based on number of pieces
            'units' => $this->resultPieces[$pieceCount - 3] === $this->resultPieces[0] ? '' : $this->resultPieces[$pieceCount - 3],
            'reference_range' => $this->resultPieces[$pieceCount - 2],
            'site_code' => $this->resultPieces[$pieceCount - 1],
        ];
    }
}
