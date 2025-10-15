<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class CorrectedSodiumCalculator extends BaseCalculator
{
    protected string $name = 'corrected_sodium';

    protected string $displayName = 'Corrected Sodium for Hyperglycemia';

    protected array $requiredFields = [
        'SODIUM,Blood',
        'GLUCOSE,Blood',
    ];

    protected string $units = 'mEq/L';

    protected int $priority = 3;

    protected string $formulaText = 'Measured Na + 1.6 × [(Glucose - 100) / 100] and Measured Na + 2.4 × [(Glucose - 100) / 100]';

    protected array $interpretationRules = [
        ['max_exclusive' => 135, 'interpretation' => 'Hyponatremia (corrected sodium range < 135 mEq/L)', 'color' => 'red-500'],
        ['min' => 135, 'max' => 145, 'interpretation' => 'Normal corrected sodium range (135-145 mEq/L)', 'color' => 'green-500'],
        ['min_exclusive' => 145, 'interpretation' => 'Hypernatremia (corrected sodium range > 145 mEq/L)', 'color' => 'red-500'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $sodiumData = $resolver->getLatestValueWithDate('SODIUM,Blood');
        $glucoseData = $resolver->getLatestValueWithDate('GLUCOSE,Blood');

        // Check if all required values are available
        if (! $sodiumData || ! $glucoseData) {
            return null;
        }

        $sodium = $sodiumData['value'];
        $glucose = $glucoseData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($sodium, 100, 200) ||
            // unnecessary to calculate if glucose is < 200
            ! $this->isValidValue($glucose, 200, 800)) {
            return null;
        }

        // Calculate corrected sodium using both formulas
        // Formula 1: Corrected Na = Measured Na + 1.6 × [(Glucose - 100) / 100]
        $correctedSodium1_6 = $sodium + 1.6 * (($glucose - 100) / 100);

        // Formula 2: Corrected Na = Measured Na + 2.4 × [(Glucose - 100) / 100]
        $correctedSodium2_4 = $sodium + 2.4 * (($glucose - 100) / 100);

        // Round to 1 decimal place
        $correctedSodium1_6 = round($correctedSodium1_6);
        $correctedSodium2_4 = round($correctedSodium2_4);

        // Create range string for display
        if ($correctedSodium1_6 == $correctedSodium2_4) {
            $correctedSodiumRange = (string) $correctedSodium1_6;
        } else {
            $correctedSodiumRange = $correctedSodium1_6.' - '.$correctedSodium2_4;
        }

        // Use the average for interpretation (or the lower value if glucose <= 100)
        $correctedSodiumForInterpretation = ($glucose <= 100) ? $correctedSodium1_6 : ($correctedSodium1_6 + $correctedSodium2_4) / 2;

        // Get interpretation with color
        $interpretationResult = $this->interpretWithColor($correctedSodiumForInterpretation);

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $correctedSodiumRange,
            units: $this->units,
            interpretation: $interpretationResult['interpretation'],
            usedValues: [
                'Measured Sodium' => $sodium,
                'Glucose' => $glucose,
            ],
            usedValueDates: [
                'Measured Sodium' => $sodiumData['collection_date'],
                'Glucose' => $glucoseData['collection_date'],
            ],
            formula: $this->formulaText,
            color: $interpretationResult['color'],
        );
    }
}
