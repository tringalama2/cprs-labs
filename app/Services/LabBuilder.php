<?php

namespace App\Services;

use App\Models\Lab;
use App\Models\UnparsableLab;
use App\Models\UnrecognizedLab;
use App\Services\DiagnosticTests\CancelledDiagnosticTest;
use App\Services\DiagnosticTests\LabCreator;
use App\Services\DiagnosticTests\UnparsableDiagnosticTest;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;

class LabBuilder extends DiagnosticTestBuilder
{
    public Collection $labLabels;

    private Collection $labCollection;

    private Collection $unparsableRows;

    private Collection $panels;

    private Collection $datetimeHeaders;

    private Collection $unrecognizedLabLabels;

    private Collection $cancelledTests;

    private Collection $labsAndPanels;

    public function __construct($rawLabs)
    {
        parent::__construct($rawLabs);
        $this->labCollection = collect();
        $this->unparsableRows = collect();
        $this->cancelledTests = collect();
        $this->setLabsAndPanels();
    }

    private function setLabsAndPanels(): void
    {
        $this->labsAndPanels = Lab::leftJoin('panels', 'labs.panel_id', '=', 'panels.id')
            ->select('labs.name', 'labs.label', 'panels.label as panel')
            ->orderBy('panels.order_column')
            ->orderBy('labs.order_column')
            ->get()->keyBy('name');
    }

    public function sort(bool $descending = true): void
    {
        $this->labCollection = $this->labCollection->sortBy(function (Collection $row, int $key) {
            return $row['collection_date']->toDateTimeString();
        }, SORT_REGULAR, $descending);
    }

    public function getDateTimeHeaders(): Collection
    {
        return $this->datetimeHeaders;
    }

    public function getLabCollection(): Collection
    {
        return $this->labCollection;
    }

    public function getUnparsableRows(): Collection
    {
        return $this->unparsableRows;
    }

    public function getCancelledTests(): Collection
    {
        return $this->cancelledTests;
    }

    public function getLabLabels(): Collection
    {
        return $this->labLabels;
    }

    public function getPanels(): Collection
    {
        return $this->panels;
    }

    public function getUnrecognizedLabLabels(): Collection
    {
        return $this->unrecognizedLabLabels;
    }

    public function build(): void
    {
        foreach ($this->labRows as $index => $row) {
            if (Row::isResult($row)) {

                $lab = (new LabCreator($this->labRows, $index))->getDiagnosticTest();

                if ($lab instanceof UnparsableDiagnosticTest) {
                    $this->unparsableRows = $this->unparsableRows->push(collect($lab->result()));
                } elseif ($lab instanceof CancelledDiagnosticTest) {
                    // Todo: do we really need this we currently aren't displaying
                    //       cancelled tests to the user
                    $this->cancelledTests = $this->cancelledTests->push(collect($lab->result()));
                } else {
                    $this->labCollection = $this->labCollection->push(collect($lab->result()));
                }
            }
        }

        $this->unrecognizedLabLabels = $this->labCollection->pluck('name')->flip()->diffKeys($this->labsAndPanels)->flip();

        $this->labLabels = $this->labsAndPanels
            ->intersectByKeys($this->labCollection->pluck('name')->flip())
            ->map(function (
                Lab $lab
            ) {
                return [
                    'name' => $lab->name,
                    'label' => $lab->label,
                    'panel' => $lab->panel,
                ];
            })
            ->toBase()  // resolves error: Call to a member function getKey() on array. when merging to empty collections; https://github.com/laravel/framework/issues/22626
            ->merge($this->unrecognizedLabLabels->flip()->map(function (
                int $key,
                string $name
            ) {
                return [
                    'name' => $name,
                    'label' => $name,
                    'panel' => 'Other',
                ];
            }));
        $this->panels = $this->labLabels->groupBy('panel')->map->count();
        $this->datetimeHeaders = $this->setCollectionDateHeaders($this->labCollection);

        $this->logUnmatchedLabs();
    }

    public function logUnmatchedLabs(): void
    {
        $this->unrecognizedLabLabels->each(function (string $item, int $key) {
            UnrecognizedLab::firstOrCreate(['name' => $item]);
        });
        $this->unparsableRows->each(function (string $item, int $key) {
            UnparsableLab::firstOrCreate(['name' => $item]);
        });
    }
}
