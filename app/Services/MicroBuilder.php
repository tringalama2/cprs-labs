<?php

namespace App\Services;

use App\Services\DiagnosticTests\MicroCreator;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;

class MicroBuilder extends DiagnosticTestBuilder
{
    private Collection $microCollection;

    public function __construct($rawLabs)
    {
        parent::__construct($rawLabs);
        $this->microCollection = collect();
    }

    public function build(): void
    {
        // TODO: Implement process() method.

        $headers = $this->labRows->filter(function (string $row) {
            return Row::isMicroHeader($row);
        });

        $headers->each(function (string $headerRow, int $microHeaderIndex) {

            $micro = (new MicroCreator($this->labRows, $microHeaderIndex))->getDiagnosticTest();

            $this->microCollection->push($micro->result());
        });
    }

    public function getMicroCollection(): Collection
    {
        return $this->microCollection;
    }
}
