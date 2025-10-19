<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class LightsCriteriaCalculator extends BaseCalculator
{
    private const int ULN_SERUM_LDH = 222;

    protected string $name = 'lights_criteria';

    protected string $displayName = "Light's Criteria for Pleural Effusions";

    protected array $requiredFields = [
        'PROTEIN,TOTAL,Blood',
        'PROTEIN,PLEURAL FLUID',
        'LDH,Blood',
        'LDH,PLEURAL FLUID',
    ];

    protected string $units = '';

    protected int $priority = 4;

    // Upper limit of normal serum LDH
    protected string $formulaText = 'Exudate if any: Pleural protein/serum protein > 0.5, OR pleural LDH/serum LDH > 0.6, OR pleural LDH > 2/3 * Serum LDH Upper Limit of Normal (222 U/L)';

    protected array $interpretationRules = [
        // This will be overridden by custom interpretation logic
        ['interpretation' => 'See detailed criteria results'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Get required values with dates
        $serumProteinData = $resolver->getLatestValueWithDate('PROTEIN,TOTAL,Blood');
        $pleuralProteinData = $resolver->getLatestValueWithDate('PROTEIN,PLEURAL FLUID');
        $serumLdhData = $resolver->getLatestValueWithDate('LDH,Blood');
        $pleuralLdhData = $resolver->getLatestValueWithDate('LDH,PLEURAL FLUID');

        // Check if all required values are available
        if (! $serumProteinData || ! $pleuralProteinData || ! $serumLdhData || ! $pleuralLdhData) {
            return null;
        }

        $serumProtein = $serumProteinData['value'];
        $pleuralProtein = $pleuralProteinData['value'];
        $serumLdh = $serumLdhData['value'];
        $pleuralLdh = $pleuralLdhData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($serumProtein, 2.0, 10.0) ||
            ! $this->isValidValue($pleuralProtein, 0.1, 20.0) ||
            ! $this->isValidValue($serumLdh, 30, 2000) ||
            ! $this->isValidValue($pleuralLdh, 30, 5000)) {
            return null;
        }

        // Avoid division by zero
        if ($serumProtein == 0 || $serumLdh == 0) {
            return null;
        }

        // Calculate Light's criteria ratios
        $proteinRatio = $pleuralProtein / $serumProtein;
        $ldhRatio = $pleuralLdh / $serumLdh;

        // Round ratios to 2 decimal places
        $proteinRatio = round($proteinRatio, 2);
        $ldhRatio = round($ldhRatio, 2);

        // Evaluate Light's criteria
        $criterion1 = $proteinRatio > 0.5; // Pleural protein/serum protein > 0.5
        $criterion2 = $ldhRatio > 0.6;     // Pleural LDH/serum LDH > 0.6
        $criterion3 = $pleuralLdh > self::ULN_SERUM_LDH; // Pleural LDH > ULN

        // Count positive criteria
        $positiveCriteria = array_sum([$criterion1, $criterion2, $criterion3]);
        $isExudate = $positiveCriteria > 0;

        // Generate detailed interpretation
        $interpretation = $this->generateDetailedInterpretation(
            $isExudate,
            $criterion1,
            $criterion2,
            $criterion3,
            $proteinRatio,
            $ldhRatio,
            $pleuralLdh,
            $positiveCriteria
        );

        // Create result summary for display
        $resultSummary = $isExudate ? 'Exudate' : 'Transudate';
        $color = $isExudate ? 'red-500' : 'green-500';

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $resultSummary,
            units: $this->units,
            interpretation: $interpretation,
            usedValues: [
                'Serum Protein' => $serumProtein,
                'Pleural Protein' => $pleuralProtein,
                'Serum LDH' => $serumLdh,
                'Pleural LDH' => $pleuralLdh,
                'Protein Ratio' => $proteinRatio,
                'LDH Ratio' => $ldhRatio,
                'Positive Criteria' => $positiveCriteria,
            ],
            usedValueDates: [
                'Serum Protein' => $serumProteinData['collection_date'],
                'Pleural Protein' => $pleuralProteinData['collection_date'],
                'Serum LDH' => $serumLdhData['collection_date'],
                'Pleural LDH' => $pleuralLdhData['collection_date'],
            ],
            formula: $this->formulaText,
            color: $color,
        );
    }

    private function generateDetailedInterpretation(
        bool $isExudate,
        bool $criterion1,
        bool $criterion2,
        bool $criterion3,
        float $proteinRatio,
        float $ldhRatio,
        float $pleuralLdh,
        int $positiveCriteria
    ): string {
        $result = $isExudate ? 'EXUDATE' : 'TRANSUDATE';

        $details = [];
        $details[] = 'Criterion 1 (Protein ratio > 0.5): '.($criterion1 ? 'POSITIVE' : 'NEGATIVE')." ({$proteinRatio})";
        $details[] = 'Criterion 2 (LDH ratio > 0.6): '.($criterion2 ? 'POSITIVE' : 'NEGATIVE')." ({$ldhRatio})";
        $details[] = 'Criterion 3 (Pleural LDH > 222): '.($criterion3 ? 'POSITIVE' : 'NEGATIVE')." ({$pleuralLdh} U/L)";

        $summary = $result." - {$positiveCriteria}/3 criteria positive. ".implode('; ', $details);

        if ($isExudate) {
            $summary .= '. Suggests exudative process (infection, malignancy, inflammation).';
        } else {
            $summary .= '. Suggests transudative process (heart failure, cirrhosis, nephrotic syndrome).';
        }

        return $summary;
    }
}
