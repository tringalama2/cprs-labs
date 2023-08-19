<?php

namespace App\Services;

use App\Enums\RowType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LabBuilder extends AbstractLabBuilder
{
    private Collection $labCollection;

    private Collection $unableToParse;

    public function __construct($rawLabs)
    {
        parent::__construct($rawLabs);
        $this->labCollection = collect();
        $this->unableToParse = collect();
    }

    public function process(): void
    {
        // TODO: Implement processPanels() method.
        foreach ($this->labRows as $index => $row) {
            if ($this->getRowType($row, $index) === RowType::Result) {
                $starRow = $index;
                $temp = collect($this->getResultMetaData($index) +
                    $this->getResultData($index));
                $this->labCollection = $this->labCollection->push($temp);
            }
        }
    }

    private function getResultMetaData($index): array
    {
        //  from result row, traverse back up to the first metadata headers
        $i = $index;
        while ($index > 0) {
            if (RowType::CollectionDate === $this->getRowType($this->labRows[$index], $index)) {
                break;
            }
            $index--;
        }

        return [
            'collection_date' => $this->stripDateFromRow($this->labRows[$index]),
            'specimen' => $this->stripSpecimenFromRow($this->labRows[$index - 1]),
            'ordering_provider' => $this->stripProviderFromRow($this->labRows[$index - 2]),
            'released_date' => $this->stripDateFromRow($this->labRows[$index - 3]),
        ];
    }

    private function stripDateFromRow(string $row): bool|Carbon
    {
        $date = Str::of($row)->match(self::DATE_PATTERN);
        if (strlen($date) === 12) {
            return Carbon::createFromFormat(self::DATETIME_CARBON_FORMAT, $date.'@00:00');
        }

        return Carbon::createFromFormat(self::DATETIME_CARBON_FORMAT, $date);
    }

    private function stripSpecimenFromRow(string $row): string
    {
        return Str::of($row)->match(self::SPECIMEN_PATTERN);
    }

    private function stripProviderFromRow(string $row): string
    {
        return Str::of($row)->substr(10);
    }

    private function getResultData($index): array
    {
        $result_array = Str::of($this->labRows[$index])->split('/(\s){2,}/')->flatten()->toArray();

        if (count($result_array) == 5) {
            return [
                'name' => $result_array[0],
                'result' => $result_array[1],
                'units' => $result_array[2],
                'ref_range' => $result_array[3],
                'site_code' => $result_array[4],
            ];
        }

        if (count($result_array) == 4) {
            return [
                'name' => $result_array[0],
                'result' => $result_array[1],
                'units' => '',
                'ref_range' => $result_array[2],
                'site_code' => $result_array[3],
            ];
        }
        $this->unableToParse->push(implode(' ', $result_array));

        return [];
    }

    public function getLabCollection(): Collection
    {
        return $this->labCollection;
    }

    public function getUnableToParseCollection(): Collection
    {
        return $this->unableToParse;
    }
}
