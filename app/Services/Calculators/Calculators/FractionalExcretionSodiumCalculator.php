<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;

class FractionalExcretionSodiumCalculator extends BaseCalculator
{
    protected string $name = 'fena';

    protected string $displayName = 'Fractional Excretion of Sodium (FENa)';

    protected array $requiredFields = [
        'CREATININE,blood',
        'SODIUM,Blood',
        'CREATININE,Urine',
        'SODIUM,Urine',
    ];

    protected string $units = '%';

    protected int $priority = 1;

    protected string $formulaText = '100 × (SCr × UNa) / (SNa × UCr)';

    protected array $interpretationRules = [
        ['max' => 1.0, 'interpretation' => 'Pre-renal azotemia likely'],
        ['min' => 2.0, 'interpretation' => 'Acute tubular necrosis likely'],
        ['interpretation' => 'Intermediate range - clinical correlation needed'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $serumCreatinineData = $resolver->getLatestValueWithDate('CREATININE,blood');
        $serumSodiumData = $resolver->getLatestValueWithDate('SODIUM,Blood');
        $urineCreatinineData = $resolver->getLatestValueWithDate('CREATININE,Urine');
        $urineSodiumData = $resolver->getLatestValueWithDate('SODIUM,Urine');

        // Check if all required values are available
        if (! $serumCreatinineData || ! $serumSodiumData || ! $urineCreatinineData || ! $urineSodiumData) {
            return null;
        }

        $serumCreatinine = $serumCreatinineData['value'];
        $serumSodium = $serumSodiumData['value'];
        $urineCreatinine = $urineCreatinineData['value'];
        $urineSodium = $urineSodiumData['value'];

        // Check if all required values are within valid ranges
        if (! $this->isValidValue($serumCreatinine, 0.1, 20) ||
            ! $this->isValidValue($serumSodium, 100, 200) ||
            ! $this->isValidValue($urineCreatinine, 1, 500) ||
            ! $this->isValidValue($urineSodium, 1, 300)) {
            return null;
        }

        // Avoid division by zero
        if ($serumSodium == 0 || $urineCreatinine == 0) {
            return null;
        }

        // Calculate FENa: 100 × (SCr × UNa) / (SNa × UCr)
        $fena = 100 * ($serumCreatinine * $urineSodium) / ($serumSodium * $urineCreatinine);

        // Round to 2 decimal places
        $fena = round($fena, 2);

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $fena,
            units: $this->units,
            interpretation: $this->interpret($fena),
            usedValues: [
                'Serum Creatinine' => $serumCreatinine,
                'Serum Sodium' => $serumSodium,
                'Urine Creatinine' => $urineCreatinine,
                'Urine Sodium' => $urineSodium,
            ],
            usedValueDates: [
                'Serum Creatinine' => $serumCreatinineData['collection_date'],
                'Serum Sodium' => $serumSodiumData['collection_date'],
                'Urine Creatinine' => $urineCreatinineData['collection_date'],
                'Urine Sodium' => $urineSodiumData['collection_date'],
            ],
            formula: $this->formulaText,
            calculatedAt: Carbon::now()
        );
    }

    /**
     * Custom interpretation logic for FENa
     */
    protected function interpret(float $value): string
    {
        if ($value < 1.0) {
            return 'Pre-renal azotemia likely';
        } elseif ($value >= 2.0) {
            return 'Acute tubular necrosis likely';
        } else {
            return 'Intermediate range - clinical correlation needed';
        }
    }
}
