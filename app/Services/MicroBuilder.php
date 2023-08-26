<?php

namespace App\Services;

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

            $this->microCollection->push(
                $this->labRows
                    ->slice($microHeaderIndex)
                    ->takeUntil(function (string $row, int $index) {
                        return Row::isSeparator($row);
                    })
            );
        });
        $this->microCollection->dd();

    }
}
