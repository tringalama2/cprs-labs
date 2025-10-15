<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class MeldCalculator extends BaseCalculator
{
    protected string $name = 'meld';

    protected string $displayName = 'Model for End-Stage Liver Disease (MELD) Score';

    protected array $requiredFields = [
        'BILIRUBIN,TOTAL,Blood',
        'CREATININE,blood',
        'INR,blood',
    ];

    protected string $units = 'points';

    protected int $priority = 7; // Lower priority than MELD-Na

    protected string $formulaText = '3.78 × ln(Bilirubin) + 11.2 × ln(INR) + 9.57 × ln(Creatinine) + 6.43';

    protected array $interpretationRules = [
        ['max' => 9, 'interpretation' => 'Low risk - 1.9% 3-month mortality', 'color' => 'green-500'],
        ['max' => 19, 'interpretation' => 'Moderate risk - 6.0% 3-month mortality', 'color' => 'yellow-500'],
        ['max' => 29, 'interpretation' => 'High risk - 19.6% 3-month mortality', 'color' => 'orange-500'],
        ['max' => 39, 'interpretation' => 'Very high risk - 52.6% 3-month mortality', 'color' => 'red-500'],
        ['interpretation' => 'Extremely high risk - >71.3% 3-month mortality', 'color' => 'purple-500'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $bilirubinData = $resolver->getLatestValueWithDate('BILIRUBIN,TOTAL,Blood');
        $creatinineData = $resolver->getLatestValueWithDate('CREATININE,blood');
        $inrData = $resolver->getLatestValueWithDate('INR,blood');

        // Check if all required values are available
        if (! $bilirubinData || ! $creatinineData || ! $inrData) {
            return null;
        }

        $bilirubin = $bilirubinData['value'];
        $creatinine = $creatinineData['value'];
        $inr = $inrData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($bilirubin, 0.1, 50) ||
            ! $this->isValidValue($creatinine, 0.1, 20) ||
            ! $this->isValidValue($inr, 0.5, 10)) {
            return null;
        }

        // Apply MELD constraints
        // Minimum values for calculation
        $bilirubin = max($bilirubin, 1.0);
        $creatinine = max($creatinine, 1.0);
        $inr = max($inr, 1.0);

        // Maximum creatinine value is capped at 4.0 for MELD calculation
        $creatinine = min($creatinine, 4.0);

        // Calculate MELD score using the standard formula
        // MELD = 3.78 × ln(Bilirubin) + 11.2 × ln(INR) + 9.57 × ln(Creatinine) + 6.43
        $meld = 3.78 * log($bilirubin) +
                11.2 * log($inr) +
                9.57 * log($creatinine) +
                6.43;

        // Round to the nearest integer
        $meld = round($meld);

        // MELD score is typically capped between 6 and 40
        $meld = max(6, min(40, $meld));

        // Get interpretation with color
        $interpretationResult = $this->interpretWithColor($meld);

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $meld,
            units: $this->units,
            interpretation: $interpretationResult['interpretation'],
            usedValues: [
                'Total Bilirubin' => $bilirubin,
                'Creatinine' => $creatinine,
                'INR' => $inr,
            ],
            usedValueDates: [
                'Total Bilirubin' => $bilirubinData['collection_date'],
                'Creatinine' => $creatinineData['collection_date'],
                'INR' => $inrData['collection_date'],
            ],
            formula: $this->formulaText,
            color: $interpretationResult['color'],
        );
    }
}
