<?php

namespace App\Services\Calculators\Core;

use App\Services\Calculators\Contracts\CalculatorInterface;
use Illuminate\Support\Collection;

abstract class BaseCalculator implements CalculatorInterface
{
    protected string $name;

    protected string $displayName;

    protected array $requiredFields;

    protected string $units;

    protected int $priority;

    protected string $formulaText;

    protected array $interpretationRules;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    public function getUnits(): string
    {
        return $this->units;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getFormulaText(): string
    {
        return $this->formulaText;
    }

    public function isApplicable(Collection $labs): bool
    {
        $resolver = new LabValueResolver($labs);

        foreach ($this->requiredFields as $field) {

            if ($resolver->getLatestValue($field) === null) {

                return false;
            }
        }

        return true;
    }

    /**
     * Interpret the calculated result based on interpretation rules
     */
    protected function interpret(float $value): string
    {
        foreach ($this->interpretationRules as $rule) {
            if (isset($rule['max']) && $value <= $rule['max']) {
                return $rule['interpretation'];
            }
            if (isset($rule['min']) && $value >= $rule['min']) {
                return $rule['interpretation'];
            }
        }

        // Return default interpretation if no rules match
        return $this->interpretationRules[array_key_last($this->interpretationRules)]['interpretation'] ?? 'Normal range';
    }

    /**
     * Validate that a numeric value is within reasonable bounds
     */
    protected function isValidValue(?float $value, float $min = 0, float $max = 1000000): bool
    {
        return $value !== null && $value >= $min && $value <= $max;
    }
}
