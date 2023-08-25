<?php

namespace App\Services;

use App\Services\Parser\RowTypes\Row;

class MicroBuilder extends DiagnosticTestBuilder
{
    public function build(): void
    {
        // TODO: Implement process() method.

        foreach ($this->labRows as $index => $row) {
            if (Row::isMicroHeader($row)) {
                $startRow = $index;
            }
            if (Row::isSeparator($row)) {
                $endRow = $index;
                $numRows = $endRow - $startRow;
                $this->getLabs()->micro[] = array_slice($this->labRows, $startRow, $numRows);
                $startRow = 0;
            }
        }
        // loop through all, mark beginning of micro

        // mark end of micro
        // array_slice to get just micro rows
        // store in micro array??
        // $this->lab->micro[] = $spliced;
    }
}
