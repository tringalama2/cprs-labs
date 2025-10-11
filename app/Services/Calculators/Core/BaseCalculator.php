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
            $matchesMin = true;
            $matchesMax = true;

            // Check minimum boundary (inclusive)
            if (isset($rule['min'])) {
                $matchesMin = $value >= $rule['min'];
            }

            // Check minimum boundary (exclusive)
            if (isset($rule['min_exclusive'])) {
                $matchesMin = $value > $rule['min_exclusive'];
            }

            // Check maximum boundary (inclusive)
            if (isset($rule['max'])) {
                $matchesMax = $value <= $rule['max'];
            }

            // Check maximum boundary (exclusive)
            if (isset($rule['max_exclusive'])) {
                $matchesMax = $value < $rule['max_exclusive'];
            }

            // If both conditions match, return this interpretation
            if ($matchesMin && $matchesMax) {
                return $rule['interpretation'];
            }
        }

        // Return default interpretation if no rules match (should be the last rule without min/max)
        foreach (array_reverse($this->interpretationRules) as $rule) {
            if (! isset($rule['min']) && ! isset($rule['max']) && ! isset($rule['max_exclusive'])) {
                return $rule['interpretation'];
            }
        }

        return 'Normal range';
    }

    /**
     * Validate that a numeric value is within reasonable bounds
     */
    protected function isValidValue(?float $value, float $min = 0, float $max = 1000000): bool
    {
        return $value !== null && $value >= $min && $value <= $max;
    }
}
