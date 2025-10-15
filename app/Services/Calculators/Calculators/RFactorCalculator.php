<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class RFactorCalculator extends BaseCalculator
{
    private const int ULN_ALT = 40;

    private const int ULN_ALP = 120;

    protected string $name = 'r_factor';

    protected string $displayName = 'R Factor for Drug-Induced Liver Injury (DILI)';

    protected array $requiredFields = [
        'ALT,Blood',
        'ALKP,Blood',
    ];

    protected string $units = '';

    protected int $priority = 8;

    protected string $formulaText = '(ALT/ULN ALT) / (ALP/ULN ALP) where ULN ALT = 40 U/L, ULN ALP = 120 U/L';

    protected array $interpretationRules = [
        ['min' => 5.0, 'interpretation' => 'Hepatocellular pattern (R ≥ 5)', 'color' => 'purple-500'],
        ['min' => 2.0, 'max_exclusive' => 5.0, 'interpretation' => 'Mixed pattern (2 ≤ R < 5)', 'color' => 'blue-500'],
        ['max_exclusive' => 2.0, 'interpretation' => 'Cholestatic pattern (R < 2)', 'color' => 'yellow-500'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $altData = $resolver->getLatestValueWithDate('ALT,Blood');
        $alpData = $resolver->getLatestValueWithDate('ALKP,Blood');

        // Check if all required values are available
        if (! $altData || ! $alpData) {
            return null;
        }

        $alt = $altData['value'];
        $alp = $alpData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($alt, 1, 5000) ||
            ! $this->isValidValue($alp, 10, 2000)) {
            return null;
        }

        // return null if both values within normal
        if ($alt <= self::ULN_ALT && $alp <= self::ULN_ALP) {
            return null;
        }

        // Calculate multiples of ULN
        $altMultiple = $alt / self::ULN_ALT;
        $alpMultiple = $alp / self::ULN_ALP;

        // Avoid division by zero
        if ($alpMultiple == 0) {
            return null;
        }

        // Calculate R Factor
        $rFactor = $altMultiple / $alpMultiple;

        // Round to 2 decimal places
        $rFactor = round($rFactor, 2);

        // Get interpretation with color
        $interpretationResult = $this->interpretWithColor($rFactor);

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $rFactor,
            units: $this->units,
            interpretation: $interpretationResult['interpretation'],
            usedValues: [
                'ALT' => $alt,
                'Alkaline Phosphatase' => $alp,
                'ALT/ULN' => round($altMultiple, 2),
                'ALP/ULN' => round($alpMultiple, 2),
            ],
            usedValueDates: [
                'ALT' => $altData['collection_date'],
                'Alkaline Phosphatase' => $alpData['collection_date'],
            ],
            formula: $this->formulaText,
            color: $interpretationResult['color'],
        );
    }
}
