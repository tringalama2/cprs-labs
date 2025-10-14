<?php

use App\Services\Calculators\Calculators\LightsCriteriaCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithLightsCriteria(
    float $serumProtein,
    float $pleuralProtein,
    float $serumLdh,
    float $pleuralLdh
): Collection {
    $testDate = Carbon::now();

    return collect([
        [
            'name' => 'PROTEIN,TOTAL,Blood',
            'result' => (string) $serumProtein,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'PROTEIN,PLEURAL FLUID',
            'result' => (string) $pleuralProtein,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'LDH,Blood',
            'result' => (string) $serumLdh,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'LDH,PLEURAL FLUID',
            'result' => (string) $pleuralLdh,
            'collection_date' => $testDate,
        ],
    ]);
}

test("Light's Criteria calculator has correct required fields", function () {
    $calculator = new LightsCriteriaCalculator();

    $expectedFields = [
        'PROTEIN,TOTAL,Blood',
        'PROTEIN,PLEURAL FLUID',
        'LDH,Blood',
        'LDH,PLEURAL FLUID',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test("Light's Criteria identifies clear transudate (0/3 criteria)", function () {
    $calculator = new LightsCriteriaCalculator();

    // Clear transudate: low protein ratio, low LDH ratio, low pleural LDH
    $labs = createLabsWithLightsCriteria(7.0, 2.0, 200, 100); // Ratios: 0.29, 0.5, LDH: 100
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Transudate');
    expect($result->usedValues['Protein Ratio'])->toBe(0.29);
    expect($result->usedValues['LDH Ratio'])->toBe(0.5);
    expect($result->usedValues['Positive Criteria'])->toBe(0);
    expect($result->interpretation)->toContain('TRANSUDATE - 0/3 criteria positive');
    expect($result->interpretation)->toContain('NEGATIVE (0.29)');
    expect($result->interpretation)->toContain('NEGATIVE (0.5)');
    expect($result->interpretation)->toContain('NEGATIVE (100 U/L)');
    expect($result->interpretation)->toContain('transudative process');
});

test("Light's Criteria identifies clear exudate (3/3 criteria)", function () {
    $calculator = new LightsCriteriaCalculator();

    // Clear exudate: high protein ratio, high LDH ratio, high pleural LDH
    $labs = createLabsWithLightsCriteria(6.0, 4.0, 200, 300); // Ratios: 0.67, 1.5, LDH: 300
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Protein Ratio'])->toBe(0.67);
    expect($result->usedValues['LDH Ratio'])->toBe(1.5);
    expect($result->usedValues['Positive Criteria'])->toBe(3);
    expect($result->interpretation)->toContain('EXUDATE - 3/3 criteria positive');
    expect($result->interpretation)->toContain('POSITIVE (0.67)');
    expect($result->interpretation)->toContain('POSITIVE (1.5)');
    expect($result->interpretation)->toContain('POSITIVE (300 U/L)');
    expect($result->interpretation)->toContain('exudative process');
});

test("Light's Criteria identifies exudate with protein criterion only (1/3)", function () {
    $calculator = new LightsCriteriaCalculator();

    // Exudate by protein ratio only
    $labs = createLabsWithLightsCriteria(6.0, 3.5, 200, 100); // Ratios: 0.58, 0.5, LDH: 100
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Protein Ratio'])->toBe(0.58);
    expect($result->usedValues['LDH Ratio'])->toBe(0.5);
    expect($result->usedValues['Positive Criteria'])->toBe(1);
    expect($result->interpretation)->toContain('EXUDATE - 1/3 criteria positive');
    expect($result->interpretation)->toContain('POSITIVE (0.58)');
    expect($result->interpretation)->toContain('NEGATIVE (0.5)');
    expect($result->interpretation)->toContain('NEGATIVE (100 U/L)');
});

test("Light's Criteria identifies exudate with LDH ratio criterion only (1/3)", function () {
    $calculator = new LightsCriteriaCalculator();

    // Exudate by LDH ratio only
    $labs = createLabsWithLightsCriteria(7.0, 3.0, 200, 150); // Ratios: 0.43, 0.75, LDH: 150
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Protein Ratio'])->toBe(0.43);
    expect($result->usedValues['LDH Ratio'])->toBe(0.75);
    expect($result->usedValues['Positive Criteria'])->toBe(1);
    expect($result->interpretation)->toContain('EXUDATE - 1/3 criteria positive');
    expect($result->interpretation)->toContain('NEGATIVE (0.43)');
    expect($result->interpretation)->toContain('POSITIVE (0.75)');
    expect($result->interpretation)->toContain('NEGATIVE (150 U/L)');
});

test("Light's Criteria identifies exudate with pleural LDH criterion only (1/3)", function () {
    $calculator = new LightsCriteriaCalculator();

    // Exudate by pleural LDH only
    $labs = createLabsWithLightsCriteria(7.0, 3.0, 500, 250); // Ratios: 0.43, 0.5, LDH: 250 > 222
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Protein Ratio'])->toBe(0.43);
    expect($result->usedValues['LDH Ratio'])->toBe(0.5);
    expect($result->usedValues['Positive Criteria'])->toBe(1);
    expect($result->interpretation)->toContain('EXUDATE - 1/3 criteria positive');
    expect($result->interpretation)->toContain('NEGATIVE (0.43)');
    expect($result->interpretation)->toContain('NEGATIVE (0.5)');
    expect($result->interpretation)->toContain('POSITIVE (250 U/L)');
});

test("Light's Criteria boundary values work correctly", function () {
    $calculator = new LightsCriteriaCalculator();

    // Test exact boundary for protein ratio (0.5)
    $labs = createLabsWithLightsCriteria(6.0, 3.0, 200, 100); // Ratio exactly 0.5
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Transudate'); // 0.5 is not > 0.5
    expect($result->usedValues['Protein Ratio'])->toBe(0.5);

    // Test exact boundary for LDH ratio (0.6)
    $labs = createLabsWithLightsCriteria(7.0, 3.0, 200, 120); // LDH ratio exactly 0.6
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Transudate'); // 0.6 is not > 0.6

    // Test exact boundary for pleural LDH (222)
    $labs = createLabsWithLightsCriteria(7.0, 3.0, 500, 222); // LDH exactly 222, ratio 0.44
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Transudate'); // 222 is not > 222
});

test("Light's Criteria just above boundaries identifies exudate", function () {
    $calculator = new LightsCriteriaCalculator();

    // Test just above protein ratio boundary (0.51)
    $labs = createLabsWithLightsCriteria(6.0, 3.06, 200, 100); // Ratio 0.51
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Protein Ratio'])->toBe(0.51);

    // Test just above LDH ratio boundary (0.61)
    $labs = createLabsWithLightsCriteria(7.0, 3.0, 200, 122); // LDH ratio 0.61
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['LDH Ratio'])->toBe(0.61);

    // Test just above pleural LDH boundary (223)
    $labs = createLabsWithLightsCriteria(7.0, 3.0, 200, 223); // LDH 223
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Pleural LDH'])->toBe(223.0);
});

test("Light's Criteria identifies exudate with multiple criteria (2/3)", function () {
    $calculator = new LightsCriteriaCalculator();

    // Exudate with protein and LDH ratio criteria
    $labs = createLabsWithLightsCriteria(6.0, 3.5, 200, 140); // Ratios: 0.58, 0.7, LDH: 140
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Positive Criteria'])->toBe(2);
    expect($result->interpretation)->toContain('EXUDATE - 2/3 criteria positive');
    expect($result->interpretation)->toContain('POSITIVE (0.58)');
    expect($result->interpretation)->toContain('POSITIVE (0.7)');
    expect($result->interpretation)->toContain('NEGATIVE (140 U/L)');
});

test("Light's Criteria returns null when required values are missing", function () {
    $calculator = new LightsCriteriaCalculator();

    // Missing pleural LDH
    $labs = collect([
        [
            'name' => 'PROTEIN,TOTAL,Blood',
            'result' => '7.0',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'PROTEIN,PLEURAL FLUID',
            'result' => '3.0',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'LDH,Blood',
            'result' => '200',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test("Light's Criteria handles invalid values correctly", function () {
    $calculator = new LightsCriteriaCalculator();

    // Test with out-of-range serum protein (too high)
    $labs = collect([
        [
            'name' => 'PROTEIN,TOTAL,Blood',
            'result' => '15', // Too high (max 10.0)
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'PROTEIN,PLEURAL FLUID',
            'result' => '3.0',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'LDH,Blood',
            'result' => '200',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'LDH,PLEURAL FLUID',
            'result' => '150',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test("Light's Criteria protects against division by zero", function () {
    $calculator = new LightsCriteriaCalculator();

    // Test with zero serum protein
    $labs = collect([
        [
            'name' => 'PROTEIN,TOTAL,Blood',
            'result' => '0', // Zero serum protein
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'PROTEIN,PLEURAL FLUID',
            'result' => '3.0',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'LDH,Blood',
            'result' => '200',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'LDH,PLEURAL FLUID',
            'result' => '150',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test("Light's Criteria used values and dates are correct", function () {
    $calculator = new LightsCriteriaCalculator();

    $labs = createLabsWithLightsCriteria(6.5, 2.5, 180, 120);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toHaveKeys([
        'Serum Protein',
        'Pleural Protein',
        'Serum LDH',
        'Pleural LDH',
        'Protein Ratio',
        'LDH Ratio',
        'Positive Criteria',
    ]);
    expect($result->usedValueDates)->toHaveKeys([
        'Serum Protein',
        'Pleural Protein',
        'Serum LDH',
        'Pleural LDH',
    ]);
    expect($result->usedValues['Serum Protein'])->toBe(6.5);
    expect($result->usedValues['Pleural Protein'])->toBe(2.5);
    expect($result->usedValues['Serum LDH'])->toBe(180.0);
    expect($result->usedValues['Pleural LDH'])->toBe(120.0);
});

test("Light's Criteria clinical scenarios work correctly", function () {
    $calculator = new LightsCriteriaCalculator();

    // Scenario 1: Heart failure (typical transudate)
    $labs = createLabsWithLightsCriteria(6.8, 2.8, 190, 95); // Ratios: 0.41, 0.5
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Transudate');
    expect($result->interpretation)->toContain('transudative process');

    // Scenario 2: Pneumonia (typical exudate)
    $labs = createLabsWithLightsCriteria(7.0, 4.5, 200, 350); // Ratios: 0.64, 1.75, LDH: 350
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Positive Criteria'])->toBe(3);
    expect($result->interpretation)->toContain('exudative process');

    // Scenario 3: Malignant effusion (borderline case)
    $labs = createLabsWithLightsCriteria(6.5, 3.3, 220, 250); // Ratios: 0.51, 1.14, LDH: 250
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('Exudate');
    expect($result->usedValues['Positive Criteria'])->toBe(3);
});
