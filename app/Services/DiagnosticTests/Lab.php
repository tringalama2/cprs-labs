<?php

namespace App\Services\DiagnosticTests;

use App\Services\Language\LanguageHelper;
use Carbon\Carbon;

class Lab implements DiagnosticTestInterface
{
    public function __construct(
        public string $name,
        public string|int|float $result,
        public Carbon $collectionDate,
        public Carbon $releasedDate,
        public string $flag,
        public string $units,
        public string $referenceRange,
        public string $specimen,
        public string $orderingProvider,
        public string $siteCode,
    ) {
    }

    public function result(): array
    {
        return [
            'name' => $this->name,
            'result' => $this->result,
            'collection_date' => $this->collectionDate,
            'released_date' => $this->releasedDate,
            'flag' => $this->flag,
            'units' => $this->units,
            'reference_range' => $this->referenceRange,
            'specimen' => $this->specimen,
            'ordering_provider' => $this->orderingProvider,
            'site_code' => $this->siteCode,
        ];
    }

    public function is_missing(): bool
    {
        return ! $this->getName($this->name);
    }

    public function getName($alias): ?string
    {
        return LanguageHelper::getName($alias);
    }
}
