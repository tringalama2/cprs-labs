<?php

namespace App\Services;

use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;

abstract class DiagnosticTestBuilder
{
    public Collection $labRows;

    protected string $rawLabs;

    public function __construct($rawLabs)
    {
        $this->rawLabs = $rawLabs;
        $this->labRows = collect($this->rawLabToRows($this->rawLabs));
        $this->fixOverflowRows();
        // repeat for some rows that overflow twice
        $this->fixOverflowRows();
        // re-index
        $this->labRows = $this->labRows->values();
    }

    private function rawLabToRows($rawLabString): array
    {
        return preg_split("/\r\n|\n|\r/", $rawLabString);
    }

    private function fixOverflowRows(): void
    {
        $overflowRows = $this->labRows->filter(function (string $row) {
            return Row::isOverflow($row);
        });

        foreach ($overflowRows as $index => $row) {
            // append overflow row to prior row
            $this->labRows[$index - 1] .= $row;
            // remove overflow
            $this->labRows->forget($index);
        }

    }

    abstract public function build(): void;

    abstract protected function setCollectionDateHeaders(Collection $testCollection): Collection;

    private function is_overflow($row): bool
    {
        return Row::isOverflow($row);
    }
}
