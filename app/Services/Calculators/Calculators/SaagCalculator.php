<?php

namespace App\Services\Calculators\Calculators;

use App\Services\Calculators\Core\BaseCalculator;
use App\Services\Calculators\Core\CalculationResult;
use App\Services\Calculators\Core\LabValueResolver;

class SaagCalculator extends BaseCalculator
{
    protected string $name = 'saag';

    protected string $displayName = 'Serum Ascites Albumin Gradient (SAAG)';

    protected array $requiredFields = [
        'ALBUMIN,PERITONEAL FLUID',
    ];

    protected string $units = 'g/dL';

    protected int $priority = 5;

    protected string $formulaText = 'Serum Albumin - Ascitic Albumin';

    protected array $interpretationRules = [
        ['min' => 1.1, 'interpretation' => 'Portal hypertension (SAAG ≥ 1.1)', 'color' => 'purple-500'],
        ['interpretation' => 'Non-portal hypertension etiology (SAAG < 1.1)', 'color' => 'orange-500'],
    ];

    public function calculate(LabValueResolver $resolver): ?CalculationResult
    {
        // Try to get serum albumin from either field name
        $serumAlbuminData = $resolver->getLatestValueWithDate('ALBUMIN,Blood')
            ?? $resolver->getLatestValueWithDate('ALBUMIN ,Blood');

        $asciticAlbuminData = $resolver->getLatestValueWithDate('ALBUMIN,PERITONEAL FLUID');

        // Check if required values are available
        if (! $serumAlbuminData || ! $asciticAlbuminData) {
            return null;
        }

        $serumAlbumin = $serumAlbuminData['value'];
        $asciticAlbumin = $asciticAlbuminData['value'];

        // Validate values are within reasonable ranges
        if (! $this->isValidValue($serumAlbumin, 1.0, 6.0) ||
            ! $this->isValidValue($asciticAlbumin, 0.1, 6.0)) {
            return null;
        }

        // Calculate SAAG
        $saag = $serumAlbumin - $asciticAlbumin;
        $saag = round($saag, 2);

        // Check for protein-based enhanced interpretation
        $proteinData = $resolver->getLatestValueWithDate('PROTEIN,PERITONEAL FLUID');
        $validProteinData = $proteinData && $this->isValidValue($proteinData['value'], 0.1, 10.0) ? $proteinData : null;
        $interpretationResult = $this->getEnhancedInterpretation($saag, $validProteinData);

        $usedValues = [
            'Serum Albumin' => $serumAlbumin,
            'Ascitic Albumin' => $asciticAlbumin,
        ];

        $usedValueDates = [
            'Serum Albumin' => $serumAlbuminData['collection_date'],
            'Ascitic Albumin' => $asciticAlbuminData['collection_date'],
        ];

        // Add protein values if available and valid
        if ($validProteinData !== null) {
            $usedValues['Ascitic Protein'] = $validProteinData['value'];
            $usedValueDates['Ascitic Protein'] = $validProteinData['collection_date'];
        }

        return new CalculationResult(
            name: $this->name,
            displayName: $this->displayName,
            value: $saag,
            units: $this->units,
            interpretation: $interpretationResult['interpretation'],
            usedValues: $usedValues,
            usedValueDates: $usedValueDates,
            formula: $this->formulaText,
            color: $interpretationResult['color'],
        );
    }

    private function getEnhancedInterpretation(float $saag, ?array $proteinData): array
    {
        if ($proteinData === null) {
            // Basic SAAG interpretation only
            return $this->interpretWithColor($saag);
        }

        $protein = $proteinData['value'];

        // Enhanced protein-based interpretation with colors
        if ($saag >= 1.1 && $protein >= 2.5) {
            return ['interpretation' => 'Cardiac ascites (SAAG ≥ 1.1, Ascitic protein ≥ 2.5)', 'color' => 'red-500'];
        } elseif ($saag >= 1.1 && $protein < 2.5) {
            return ['interpretation' => 'Cirrhosis (SAAG ≥ 1.1, Ascitic protein < 2.5)', 'color' => 'purple-500'];
        } elseif ($saag < 1.1 && $protein < 2.5) {
            return ['interpretation' => 'Nephrotic syndrome (SAAG < 1.1, Ascitic protein < 2.5)', 'color' => 'orange-500'];
        } elseif ($saag < 1.1 && $protein > 2.5) {
            return ['interpretation' => 'TB or Malignancy (SAAG < 1.1, Ascitic protein > 2.5)', 'color' => 'red-500'];
        }

        // Fallback to basic interpretation
        return $this->interpretWithColor($saag);
    }
}
