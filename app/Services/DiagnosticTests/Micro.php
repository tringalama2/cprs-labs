<?php

namespace App\Services\DiagnosticTests;

use Carbon\Carbon;

class Micro implements DiagnosticTestInterface
{
    public function __construct(
        public string $accessionUniqueId,
        public string $name,
        public string $result,
        public Carbon $collectionDate,
        public Carbon|bool $completedDate,
        public string $sample,
        public string $specimen,
        public string $orderingProvider,
    ) {
    }

    public function result(): array
    {
        return [
            'accession_unique_id' => $this->accessionUniqueId,
            'name' => $this->name,
            'result' => $this->result,
            'collection_date' => $this->collectionDate,
            'released_date' => $this->completedDate,
            'sample' => $this->sample,
            'specimen' => $this->specimen,
            'ordering_provider' => $this->orderingProvider,
        ];
    }
}
