<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class MeldNaCalculator extends BaseCalculator
{
    protected string $name = 'meld_na';

    protected string $displayName = 'Model for End-Stage Liver Disease with Sodium (MELD-Na) Score';

    protected array $requiredFields = [
        'BILIRUBIN,TOTAL,Blood',
        'CREATININE,blood',
        'INR,blood',
        'SODIUM,Blood',
    ];

    protected string $units = 'points';

    protected int $priority = 1; // Higher priority than original MELD

    protected string $formulaText = 'MELD + 1.32 × (137 - Na) - [0.033 × MELD × (137 - Na)] (if Na < 137)';

    protected array $interpretationRules = [
        ['max' => 9, 'interpretation' => 'Low risk - 1.9% 3-month mortality'],
        ['max' => 19, 'interpretation' => 'Moderate risk - 6.0% 3-month mortality'],
        ['max' => 29, 'interpretation' => 'High risk - 19.6% 3-month mortality'],
        ['max' => 39, 'interpretation' => 'Very high risk - 52.6% 3-month mortality'],
        ['interpretation' => 'Extremely high risk - >71.3% 3-month mortality'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $bilirubinData = $resolver->getLatestValueWithDate('BILIRUBIN,TOTAL,Blood');
        $creatinineData = $resolver->getLatestValueWithDate('CREATININE,blood');
        $inrData = $resolver->getLatestValueWithDate('INR,blood');
        $sodiumData = $resolver->getLatestValueWithDate('SODIUM,Blood');

        // Check if all required values are available
        if (! $bilirubinData || ! $creatinineData || ! $inrData || ! $sodiumData) {
            return null;
        }

        $bilirubin = $bilirubinData['value'];
        $creatinine = $creatinineData['value'];
        $inr = $inrData['value'];
        $sodium = $sodiumData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($bilirubin, 0.1, 50) ||
            ! $this->isValidValue($creatinine, 0.1, 20) ||
            ! $this->isValidValue($inr, 0.5, 10) ||
            ! $this->isValidValue($sodium, 100, 200)) {
            return null;
        }

        // Calculate original MELD score first
        // Apply MELD constraints
        $bilirubinForMeld = max($bilirubin, 1.0);
        $creatinineForMeld = max($creatinine, 1.0);
        $creatinineForMeld = min($creatinineForMeld, 4.0); // Cap at 4.0
        $inrForMeld = max($inr, 1.0);

        // Calculate base MELD score
        $meld = 3.78 * log($bilirubinForMeld) +
                11.2 * log($inrForMeld) +
                9.57 * log($creatinineForMeld) +
                6.43;

        // Round MELD to nearest integer and cap between 6 and 40
        $meld = max(6, min(40, round($meld)));

        // Apply sodium adjustment for MELD-Na
        // Sodium is capped between 125 and 137 for calculation
        $sodiumForCalc = max(125, min(137, $sodium));

        // Calculate MELD-Na
        if ($sodiumForCalc >= 137) {
            // No sodium adjustment needed
            $meldNa = $meld;
        } else {
            // Apply sodium adjustment formula
            $sodiumAdjustment = 1.32 * (137 - $sodiumForCalc) - (0.033 * $meld * (137 - $sodiumForCalc));
            $meldNa = $meld + $sodiumAdjustment;
        }

        // Round to nearest integer and ensure reasonable bounds
        $meldNa = max(6, min(40, round($meldNa)));

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $meldNa,
            units: $this->units,
            interpretation: $this->interpret($meldNa),
            usedValues: [
                'Total Bilirubin' => $bilirubin,
                'Creatinine' => $creatinine,
                'INR' => $inr,
                'Serum Sodium' => $sodium,
                'Base MELD Score' => $meld,
            ],
            usedValueDates: [
                'Total Bilirubin' => $bilirubinData['collection_date'],
                'Creatinine' => $creatinineData['collection_date'],
                'INR' => $inrData['collection_date'],
                'Serum Sodium' => $sodiumData['collection_date'],
            ],
            formula: $this->formulaText,
        );
    }
}
