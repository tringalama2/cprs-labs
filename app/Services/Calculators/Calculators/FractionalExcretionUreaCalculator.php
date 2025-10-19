<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class FractionalExcretionUreaCalculator extends BaseCalculator
{
    protected string $name = 'feurea';

    protected string $displayName = 'Fractional Excretion of Urea (FEUrea)';

    protected array $requiredFields = [
        'CREATININE,blood',
        'UREA NITROGEN,Blood',
        'CREATININE,Urine',
        'UREA NITROGEN,Urine',
    ];

    protected string $units = '%';

    protected int $priority = 2;

    protected string $formulaText = '100 × (SCr × UUrea) / (SUrea × UCr)';

    protected array $interpretationRules = [
        ['max_exclusive' => 35.0, 'interpretation' => 'Pre-renal azotemia likely', 'color' => 'blue-500'],
        ['min' => 50.0, 'interpretation' => 'Acute tubular necrosis likely', 'color' => 'blue-500'],
        ['interpretation' => 'Intermediate range - clinical correlation needed', 'color' => 'red-500'], // fallback for 35-50
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $serumCreatinineData = $resolver->getLatestValueWithDate('CREATININE,blood');
        $serumUreaData = $resolver->getLatestValueWithDate('UREA NITROGEN,Blood');
        $urineCreatinineData = $resolver->getLatestValueWithDate('CREATININE,Urine');
        $urineUreaData = $resolver->getLatestValueWithDate('UREA NITROGEN,Urine');

        // Check if all required values are available
        if (! $serumCreatinineData || ! $serumUreaData || ! $urineCreatinineData || ! $urineUreaData) {
            return null;
        }

        $serumCreatinine = $serumCreatinineData['value'];
        $serumUrea = $serumUreaData['value'];
        $urineCreatinine = $urineCreatinineData['value'];
        $urineUrea = $urineUreaData['value'];

        // Check if all required values are within valid ranges
        if (! $this->isValidValue($serumCreatinine, 0.1, 20) ||
            ! $this->isValidValue($serumUrea, 1, 300) ||
            ! $this->isValidValue($urineCreatinine, 1, 500) ||
            ! $this->isValidValue($urineUrea, 1, 2000)) {
            return null;
        }

        // Avoid division by zero
        if ($serumUrea == 0 || $urineCreatinine == 0) {
            return null;
        }

        // Calculate FEUrea: 100 × (SCr × UUrea) / (SUrea × UCr)
        $feurea = 100 * ($serumCreatinine * $urineUrea) / ($serumUrea * $urineCreatinine);

        // Round to 2 decimal places
        $feurea = round($feurea, 2);

        // Get interpretation with color
        $interpretationResult = $this->interpretWithColor($feurea);

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $feurea,
            units: $this->units,
            interpretation: $interpretationResult['interpretation'],
            color: $interpretationResult['color'],
            usedValues: [
                'Serum Creatinine' => $serumCreatinine,
                'Serum Urea Nitrogen' => $serumUrea,
                'Urine Creatinine' => $urineCreatinine,
                'Urine Urea Nitrogen' => $urineUrea,
            ],
            usedValueDates: [
                'Serum Creatinine' => $serumCreatinineData['collection_date'],
                'Serum Urea Nitrogen' => $serumUreaData['collection_date'],
                'Urine Creatinine' => $urineCreatinineData['collection_date'],
                'Urine Urea Nitrogen' => $urineUreaData['collection_date'],
            ],
            formula: $this->formulaText,
        );
    }
}
