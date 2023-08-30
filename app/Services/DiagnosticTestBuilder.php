<?php

namespace App\Services;

use App\Services\Parser\RowTypes\Row;
use Carbon\Carbon;
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

    protected function setCollectionDateHeaders(Collection $labCollection): Collection
    {
        return $labCollection
            ->pluck('collection_date')
            ->unique()
            ->map(function ($item) {
                return Carbon::parse($item)->format('n/j/y<b\r>G:i');
            });
    }

    private function is_overflow($row): bool
    {
        return Row::isOverflow($row);
    }
}
