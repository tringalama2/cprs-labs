<?php

namespace App\Services;

use App\Enums\RowType;

class MicroBuilder extends AbstractLabBuilder
{
    public function process(): void
    {
        // TODO: Implement process() method.

        foreach ($this->labRows as $index => $row) {
            if ($this->getRowType($row, $index) === RowType::MicroHeader) {
                $starRow = $index;
            }
            if ($this->getRowType($row, $index) === RowType::SeparatorLine) {
                $endRow = $index;
                $this->getLabs()->micro[] = array_slice($this->labRows);
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
