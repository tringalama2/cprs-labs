<?php

namespace App\Services\Labs;

use App\Services\Language\LanguageHelper;
use DateTime;

class Lab implements LabInterface
{
    public string $name;

    public string|int|float $result;

    public DateTime $collectDate;

    public DateTime $resultDate;

    public string $flag;

    public string $units;

    public string $referenceRange;

    public string $specimen;

    public string $orderingProvider;

    public function __construct($row)
    {

    }

    public function display(): string
    {
        // TODO: Implement display() method.
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
