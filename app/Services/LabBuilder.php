<?php

namespace App\Services;

use App\Services\DiagnosticTests\LabCreator;
use App\Services\DiagnosticTests\UnparsableDiagnosticTest;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;

class LabBuilder extends DiagnosticTestBuilder
{
    private Collection $labCollection;

    private Collection $unparsableRowsCollection;

    public function __construct($rawLabs)
    {
        parent::__construct($rawLabs);
        $this->labCollection = collect();
        $this->unparsableRowsCollection = collect();
    }

    public function process(): void
    {
        // TODO: Implement processPanels() method.
        foreach ($this->labRows as $index => $row) {

            if (Row::isResult($row)) {
                $lab = (new LabCreator($this->labRows, $index))->getDiagnosticTest();
                if ($lab instanceof UnparsableDiagnosticTest) {
                    $this->unparsableRowsCollection = $this->unparsableRowsCollection->push(collect($lab->result()));
                } else {
                    $this->labCollection = $this->labCollection->push(collect($lab->result()));
                }
            }
        }
    }

    public function getLabCollection(): Collection
    {
        return $this->labCollection;
    }

    public function getUnparsableRowsCollection(): Collection
    {
        return $this->unparsableRowsCollection;
    }
}
