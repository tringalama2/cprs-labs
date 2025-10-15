<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class CorrectedCalciumCalculator extends BaseCalculator
{
    private const float NORMAL_ALBUMIN = 4.0;

    protected string $name = 'corrected_calcium';

    protected string $displayName = 'Corrected Calcium for Albumin';

    protected array $requiredFields = [
        'CALCIUM,Blood',
    ];

    protected string $units = 'mg/dL';

    protected int $priority = 9;

    protected string $formulaText = 'Measured Calcium + 0.8 × (4.0 - Serum Albumin)';

    protected array $interpretationRules = [
        ['max_exclusive' => 8.5, 'interpretation' => 'Hypocalcemia (corrected calcium < 8.5 mg/dL)', 'color' => 'red-500'],
        ['min' => 8.5, 'max' => 10.5, 'interpretation' => 'Normal corrected calcium (8.5-10.5 mg/dL)', 'color' => 'green-500'],
        ['min_exclusive' => 10.5, 'interpretation' => 'Hypercalcemia (corrected calcium > 10.5 mg/dL)', 'color' => 'red-500'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Try to get albumin from either field name (with or without space)
        $albuminData = $resolver->getLatestValueWithDate('ALBUMIN,Blood')
            ?? $resolver->getLatestValueWithDate('ALBUMIN ,Blood');

        $calciumData = $resolver->getLatestValueWithDate('CALCIUM,Blood');

        // Check if all required values are available
        if (! $albuminData || ! $calciumData) {
            return null;
        }

        $albumin = $albuminData['value'];
        $calcium = $calciumData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($albumin, 1.0, 6.0) ||
            ! $this->isValidValue($calcium, 4.0, 15.0)) {
            return null;
        }

        // Calculate corrected calcium using standard formula
        // Corrected Calcium = Measured Calcium + 0.8 × (4.0 - Serum Albumin)
        $correctedCalcium = $calcium + 0.8 * (self::NORMAL_ALBUMIN - $albumin);

        // Round to 1 decimal place
        $correctedCalcium = round($correctedCalcium, 1);

        // Get interpretation with color
        $interpretationResult = $this->interpretWithColor($correctedCalcium);

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $correctedCalcium,
            units: $this->units,
            interpretation: $interpretationResult['interpretation'],
            usedValues: [
                'Measured Calcium' => $calcium,
                'Serum Albumin' => $albumin,
            ],
            usedValueDates: [
                'Measured Calcium' => $calciumData['collection_date'],
                'Serum Albumin' => $albuminData['collection_date'],
            ],
            formula: $this->formulaText,
            color: $interpretationResult['color'],
        );
    }
}
