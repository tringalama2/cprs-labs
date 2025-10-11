<?php

namespace App\Services\Calculators;

use App\Services\Calculators\Core\CalculatorRegistry;
use Illuminate\Support\Collection;

class CalculationEngine
{
    private CalculatorRegistry $registry;

    public function __construct()
    {
        $this->registry = new CalculatorRegistry();
    }

    /**
     * Calculate all applicable calculators for the given lab collection
     */
    public function calculate(Collection $labs): Collection
    {
        return $this->registry->calculateAll($labs);
    }

    /**
     * Get available calculator information
     */
    public function getAvailableCalculators(): Collection
    {
        return $this->registry->getAllCalculators()->map(function ($calculator) {
            return [
                'name' => $calculator->getName(),
                'display_name' => $calculator->getDisplayName(),
                'required_fields' => $calculator->getRequiredFields(),
                'units' => $calculator->getUnits(),
                'formula' => $calculator->getFormulaText(),
                'priority' => $calculator->getPriority(),
            ];
        });
    }

    /**
     * Check which calculators can be applied to the given labs
     */
    public function getApplicableCalculatorInfo(Collection $labs): Collection
    {
        return $this->registry->getApplicableCalculators($labs)->map(function ($calculator) {
            return [
                'name' => $calculator->getName(),
                'display_name' => $calculator->getDisplayName(),
                'can_calculate' => true,
            ];
        });
    }

    /**
     * Debug method to see what lab names are available
     */
    public function debugLabNames(Collection $labs): array
    {
        return $labs->pluck('name')->unique()->sort()->values()->toArray();
    }
}
