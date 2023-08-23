<?php

namespace App\Services;

use App\Exceptions\LabBuilderEmptyCollectionException;
use App\Models\Lab;
use App\Services\DiagnosticTests\LabCreator;
use App\Services\DiagnosticTests\UnparsableDiagnosticTest;
use App\Services\Parser\RowTypes\Row;
use Carbon\Carbon;
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

    /**
     * @throws LabBuilderEmptyCollectionException
     */
    public function sort(bool $descending = true): void
    {
        $this->verifyLabCollectionNotEmpty();

        $this->labCollection = $this->labCollection->sortBy(function (Collection $row, int $key) {
            return $row['collection_date']->toDateTimeString();
        }, SORT_REGULAR, true);
    }

    /**
     * @throws LabBuilderEmptyCollectionException
     */
    private function verifyLabCollectionNotEmpty(): void
    {
        if ($this->labCollection->isEmpty()) {
            $this->process();
            if ($this->labCollection->isEmpty()) {
                throw new LabBuilderEmptyCollectionException('Lab Collection is empty.');
            }
        }
    }

    public function process(): void
    {
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

    /**
     * @throws LabBuilderEmptyCollectionException
     */
    public function getLabLabels(): Collection
    {
        $this->verifyLabCollectionNotEmpty();
        $labsAndPanels = $this->getLabsAndPanels();

        return $labsAndPanels->intersectByKeys($this->labCollection->pluck('name')->flip());
    }

    private function getLabsAndPanels(): Collection
    {
        return cache()->remember('availableLabs', 60, function () {
            return Lab::leftJoin('panels', 'labs.panel_id', '=', 'panels.id')
                ->select('labs.name', 'labs.label', 'panels.label as panel')
                ->orderBy('panels.sort_id')
                ->orderBy('labs.sort_id')
                ->get()->keyBy('name');
        });
    }

    public function getUnrecognizedLabs(): Collection
    {
        $this->verifyLabCollectionNotEmpty();
        $labsAndPanels = $this->getLabsAndPanels();
        $unrecognized = $this->getUnrecognizedLabLabels()->flip();

        return $this->labCollection->filter(function (Collection $value, int $key) use ($unrecognized) {
            return $unrecognized->has($value->get('name'));
        });
    }

    public function getUnrecognizedLabLabels(): Collection
    {
        $this->verifyLabCollectionNotEmpty();
        $labsAndPanels = $this->getLabsAndPanels();

        return $this->labCollection->pluck('name')->flip()->diffKeys($labsAndPanels)->flip();

    }

    /**
     * @throws LabBuilderEmptyCollectionException
     */
    public function getCollectionDateHeaders(): Collection
    {
        $this->verifyLabCollectionNotEmpty();

        return $this->labCollection
            ->pluck('collection_date')
            ->unique()
            ->map(function ($item) {
                return Carbon::parse($item)->format('n/j/y<b\r>G:i');
            });
    }
}
