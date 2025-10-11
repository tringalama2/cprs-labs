<?php

namespace App\Services\Calculators\Core;

use Carbon\Carbon;

class CalculationResult
{
    public function __construct(
        public readonly string $name,
        public readonly string $displayName,
        public readonly float|string $value,
        public readonly string $units,
        public readonly string $interpretation,
        public readonly array $usedValues,
        public readonly array $usedValueDates,
        public readonly string $formula,
        public readonly Carbon $calculatedAt
    ) {
        // Constructor - all properties are now required
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'display_name' => $this->displayName,
            'value' => $this->value,
            'units' => $this->units,
            'display_value' => $this->getDisplayValue(),
            'interpretation' => $this->interpretation,
            'formula' => $this->formula,
            'used_values' => $this->usedValues,
            'used_value_dates' => $this->usedValueDates,
            'calculated_at' => $this->calculatedAt?->toISOString(),
        ];
    }

    /**
     * Get full display string with value and units
     */
    public function getDisplayValue(): string
    {
        $formatted = is_string($this->value) ? $this->value : (string) $this->value;

        return $this->units ? "$formatted ".$this->units : $formatted;
    }

    /**
     * Check if the result indicates an abnormal/concerning value
     */
    public function isAbnormal(): bool
    {
        $interpretation = strtolower($this->interpretation);

        $abnormalIndicators = [
            'high', 'low', 'elevated', 'decreased', 'abnormal',
            'concerning', 'critical', 'severe', 'moderate',
        ];

        foreach ($abnormalIndicators as $indicator) {
            if (str_contains($interpretation, $indicator)) {
                return true;
            }
        }

        return false;
    }
}
