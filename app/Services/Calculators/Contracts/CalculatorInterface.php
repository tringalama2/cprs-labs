<?php

namespace App\Services\Calculators\Contracts;

use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;
use Illuminate\Support\Collection;

interface CalculatorInterface
{
    /**
     * Get the unique name of the calculator
     */
    public function getName(): string;

    /**
     * Get the display name for the calculator
     */
    public function getDisplayName(): string;

    /**
     * Get the required lab fields for this calculator
     */
    public function getRequiredFields(): array;

    /**
     * Get the units for the calculated result
     */
    public function getUnits(): string;

    /**
     * Get the display priority (lower numbers display first)
     */
    public function getPriority(): int;

    /**
     * Get the formula description for display
     */
    public function getFormulaText(): string;

    /**
     * Check if this calculator can be applied to the given lab collection
     */
    public function isApplicable(Collection $labs): bool;

    /**
     * Perform the calculation
     */
    public function calculate(LabValueResolver $resolver): ?CalculationResult;
}
