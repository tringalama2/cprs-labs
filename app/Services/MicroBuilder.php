<?php

namespace App\Services;

use App\Models\Micro;
use App\Services\DiagnosticTests\MicroCreator;
use App\Services\Parser\RowTypes\Row;
use Illuminate\Support\Collection;

class MicroBuilder extends DiagnosticTestBuilder
{
    private Collection $datetimeHeaders;

    private Collection $microCollection;

    private Collection $microsAndPanel;

    private Collection $microLabels;

    private Collection $unrecognizedMicroLabels;

    public function __construct($rawLabs)
    {
        parent::__construct($rawLabs);
        $this->microCollection = collect();
        $this->setLabsAndPanels();
    }

    private function setLabsAndPanels(): void
    {
        $this->microsAndPanel = Micro::query()
            ->selectRaw("name, label, 'Micro' as panel")
            ->orderBy('order_column')
            ->get()->keyBy('name');
    }

    public function getMicroLabels(): Collection
    {
        return $this->microLabels;
    }

    public function build(): void
    {
        // TODO: Implement process() method.

        $headers = $this->labRows->filter(function (string $row) {
            return Row::isMicroHeader($row);
        });

        $headers->each(function (string $headerRow, int $microHeaderIndex) {

            $micro = (new MicroCreator($this->labRows, $microHeaderIndex))->getDiagnosticTest();

            $this->microCollection->push(collect($micro->result()));
        });

        $this->datetimeHeaders = $this->setCollectionDateHeaders($this->microCollection);

        $this->unrecognizedMicroLabels = $this->microCollection->pluck('name')->flip()->diffKeys($this->microsAndPanel)->flip();

        $this->microLabels = $this->microsAndPanel
            ->intersectByKeys($this->microCollection->pluck('name')->flip())
            ->map(function (
                Micro $micro
            ) {
                return [
                    'name' => $micro->name,
                    'label' => $micro->label,
                    'panel' => 'Micro',
                ];
            })->merge($this->unrecognizedMicroLabels->flip()->map(function (
                int $key,
                string $name
            ) {
                return [
                    'name' => $name,
                    'label' => $name,
                    'panel' => 'Micro',
                ];
            }));
    }

    public function getMicroCollection(): Collection
    {
        return $this->microCollection;
    }

    public function getDateTimeHeaders(): Collection
    {
        return $this->datetimeHeaders;
    }
}
