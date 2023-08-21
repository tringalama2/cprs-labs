<?php

namespace App\Services;

use App\Exceptions\LabBuilderEmptyCollectionException;
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

        return $this->labCollection
            ->pluck('name')
            ->unique()
            ->sortBy(function (?string $name, int $key) {
                $order = array_search($name, include(app_path('Services/Format/sort.php')));
                if ($order === false) {
                    return 10000;
                }

                return $order;
            })
            ->flip()
            ->map(function (int $value, string $key) {
                return array_search($key, array_flip(include app_path('Services/Language/aliases.php')));
            });
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
