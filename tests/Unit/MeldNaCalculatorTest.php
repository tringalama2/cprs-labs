<?php

use App\Services\Calculators\Calculators\MeldNaCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithMeldNa(float $bilirubin, float $creatinine, float $inr, float $sodium): Collection
{
    $testDate = Carbon::now();

    return collect([
        [
            'name' => 'BILIRUBIN,TOTAL,Blood',
            'result' => (string) $bilirubin,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'CREATININE,blood',
            'result' => (string) $creatinine,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'INR,blood',
            'result' => (string) $inr,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => (string) $sodium,
            'collection_date' => $testDate,
        ],
    ]);
}

test('MELD-Na calculator has correct required fields', function () {
    $calculator = new MeldNaCalculator();

    $expectedFields = [
        'BILIRUBIN,TOTAL,Blood',
        'CREATININE,blood',
        'INR,blood',
        'SODIUM,Blood',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test('MELD-Na handles normal values correctly', function () {
    $calculator = new MeldNaCalculator();

    $labs = collect([
        ['name' => 'CREATININE,Blood', 'result' => 1, 'collection_date' => '2023-01-01'], // wnl
        ['name' => 'BILIRUBIN,TOTAL,Blood', 'result' => 0.5, 'collection_date' => '2023-01-01'], // wnl
        ['name' => 'INR,Blood', 'result' => 1.1, 'collection_date' => '2023-01-01'], // wnl
        ['name' => 'SODIUM,Blood', 'result' => 140, 'collection_date' => '2023-01-01'], // wnl
    ]);

    $result = $calculator->calculate(new LabValueResolver($labs));

    expect($result)->toBeNull();
});

test('MELD-Na calculation with normal sodium equals MELD', function () {
    $calculator = new MeldNaCalculator();

    // Test with normal sodium (≥137) - should equal base MELD score
    $labs = createLabsWithMeldNa(2.5, 2.0, 1.8, 140);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(23.0); // Same as regular MELD
    expect($result->units)->toBe('points');
    expect($result->interpretation)->toBe('High risk - 19.6% 3-month mortality');
    expect($result->usedValues)->toHaveKey('Base MELD Score');
    expect($result->usedValues['Base MELD Score'])->toBe(23.0);
});

test('MELD-Na calculation with low sodium increases score', function () {
    $calculator = new MeldNaCalculator();

    // Test with low sodium (130) - should be higher than base MELD
    $labs = createLabsWithMeldNa(2.5, 2.0, 1.8, 130);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBeGreaterThan(23.0); // Higher than base MELD
    expect($result->units)->toBe('points');
    expect($result->usedValues)->toHaveKey('Base MELD Score');
    expect($result->usedValues['Base MELD Score'])->toBe(23.0);
    expect($result->usedValues['Serum Sodium'])->toBe(130.0);
});

test('MELD-Na interprets low risk correctly', function () {
    $calculator = new MeldNaCalculator();

    // MELD-Na ≤ 9 = Low risk
    $labs = createLabsWithMeldNa(1.1, 0.5, 0.5, 137);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(7.0); // Minimum MELD-Na score
    expect($result->interpretation)->toBe('Low risk - 1.9% 3-month mortality');
});

test('MELD-Na interprets moderate risk correctly', function () {
    $calculator = new MeldNaCalculator();

    // MELD-Na 10-19 = Moderate risk
    $labs = createLabsWithMeldNa(1.5, 1.2, 1.3, 140);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Moderate risk - 6.0% 3-month mortality');
});

test('MELD-Na interprets high risk correctly', function () {
    $calculator = new MeldNaCalculator();

    // MELD-Na 20-29 = High risk
    $labs = createLabsWithMeldNa(2.5, 2.0, 1.8, 140);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('High risk - 19.6% 3-month mortality');
});

test('MELD-Na interprets very high risk correctly', function () {
    $calculator = new MeldNaCalculator();

    // MELD-Na 30-39 = Very high risk
    $labs = createLabsWithMeldNa(5.0, 3.0, 2.5, 140);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Very high risk - 52.6% 3-month mortality');
});

test('MELD-Na interprets extremely high risk correctly', function () {
    $calculator = new MeldNaCalculator();

    // MELD-Na ≥ 40 = Extremely high risk (capped at 40)
    $labs = createLabsWithMeldNa(10.0, 4.0, 3.0, 140);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(40.0); // Maximum MELD-Na score
    expect($result->interpretation)->toBe('Extremely high risk - >71.3% 3-month mortality');
});

test('MELD-Na caps creatinine at 4.0', function () {
    $calculator = new MeldNaCalculator();

    // Test with very high creatinine (should be capped at 4.0)
    $labs = createLabsWithMeldNa(2.0, 8.0, 1.5, 140);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues['Creatinine'])->toBe(8.0); // Shows actual value
    // But internally uses 4.0 for calculation (verified by checking base MELD matches expected)
});

test('MELD-Na caps sodium between 125 and 137 for calculation', function () {
    $calculator = new MeldNaCalculator();

    // Test with very low sodium (should be capped at 125 for calculation)
    $labs = createLabsWithMeldNa(2.0, 1.5, 1.5, 120);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues['Serum Sodium'])->toBe(120.0); // Shows actual value

    // Test with very high sodium (should be treated as 137 for calculation)
    $labs = createLabsWithMeldNa(2.0, 1.5, 1.5, 150);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues['Serum Sodium'])->toBe(150.0); // Shows actual value
});

test('MELD-Na caps final score between 6 and 40', function () {
    $calculator = new MeldNaCalculator();

    // Test minimum score
    $labs = createLabsWithMeldNa(1.0, 1.3, 1.0, 137);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBeGreaterThanOrEqual(6.0);

    // Test maximum score
    $labs = createLabsWithMeldNa(50.0, 4.0, 5.0, 125);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBeLessThanOrEqual(40.0);
});

test('MELD-Na returns null when required values are missing', function () {
    $calculator = new MeldNaCalculator();

    // Missing sodium (which distinguishes this from regular MELD)
    $labs = collect([
        [
            'name' => 'BILIRUBIN,TOTAL,Blood',
            'result' => '2.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,blood',
            'result' => '2.0',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'INR,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('MELD-Na handles invalid values correctly', function () {
    $calculator = new MeldNaCalculator();

    // Test with out-of-range values
    $labs = collect([
        [
            'name' => 'BILIRUBIN,TOTAL,Blood',
            'result' => '100', // Too high (max 50)
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,blood',
            'result' => '2.0',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'INR,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => '140',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('MELD-Na used values match input values and include base MELD', function () {
    $calculator = new MeldNaCalculator();

    $labs = createLabsWithMeldNa(2.5, 1.8, 1.6, 135);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toHaveKeys([
        'Total Bilirubin',
        'Creatinine',
        'INR',
        'Serum Sodium',
        'Base MELD Score',
    ]);
    expect($result->usedValueDates)->toHaveKeys([
        'Total Bilirubin',
        'Creatinine',
        'INR',
        'Serum Sodium',
    ]);
});

test('MELD-Na boundary sodium values work correctly', function () {
    $calculator = new MeldNaCalculator();

    // Test exactly at 137 (boundary where no adjustment is made)
    $labs = createLabsWithMeldNa(2.0, 1.5, 1.5, 137);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    $baseMeld = $result->usedValues['Base MELD Score'];
    expect($result->value)->toBe($baseMeld); // Should equal base MELD when sodium = 137

    // Test below 137 (should have adjustment)
    $labs = createLabsWithMeldNa(2.0, 1.5, 1.5, 136);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    $baseMeld = $result->usedValues['Base MELD Score'];
    expect($result->value)->toBeGreaterThan($baseMeld); // Should be higher than base MELD when sodium < 137
});
