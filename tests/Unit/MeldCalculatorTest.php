<?php

use App\Services\Calculators\Calculators\MeldCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithMeld(float $bilirubin, float $creatinine, float $inr): Collection
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
    ]);
}

test('MELD calculator has correct required fields', function () {
    $calculator = new MeldCalculator();

    $expectedFields = [
        'BILIRUBIN,TOTAL,Blood',
        'CREATININE,blood',
        'INR,blood',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test('MELD calculator has correct properties', function () {
    $calculator = new MeldCalculator();

    expect($calculator->getName())->toBe('meld');
    expect($calculator->getDisplayName())->toBe('MELD Score (Model for End-Stage Liver Disease)');
    expect($calculator->getUnits())->toBe('points');
    expect($calculator->getFormulaText())->toBe('3.78 × ln(Bilirubin) + 11.2 × ln(INR) + 9.57 × ln(Creatinine) + 6.43');
    expect($calculator->getPriority())->toBe(2);
});

test('MELD calculation formula is correct', function () {
    $calculator = new MeldCalculator();

    // Test case: MELD = 3.78 × ln(2.5) + 11.2 × ln(1.8) + 9.57 × ln(2.0) + 6.43 = 23
    $labs = createLabsWithMeld(2.5, 2.0, 1.8);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(23.0);
    expect($result->units)->toBe('points');
    expect($result->interpretation)->toBe('High risk - 19.6% 3-month mortality');
});

test('MELD interprets low risk correctly', function () {
    $calculator = new MeldCalculator();

    // MELD ≤ 9 = Low risk
    $labs = createLabsWithMeld(1.0, 1.0, 1.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(6.0); // Minimum MELD score
    expect($result->interpretation)->toBe('Low risk - 1.9% 3-month mortality');
});

test('MELD interprets moderate risk correctly', function () {
    $calculator = new MeldCalculator();

    // MELD 10-19 = Moderate risk
    $labs = createLabsWithMeld(1.5, 1.2, 1.3);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Moderate risk - 6.0% 3-month mortality');
});

test('MELD interprets high risk correctly', function () {
    $calculator = new MeldCalculator();

    // MELD 20-29 = High risk
    $labs = createLabsWithMeld(2.5, 2.0, 1.8);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('High risk - 19.6% 3-month mortality');
});

test('MELD interprets very high risk correctly', function () {
    $calculator = new MeldCalculator();

    // MELD 30-39 = Very high risk
    $labs = createLabsWithMeld(5.0, 3.0, 2.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Very high risk - 52.6% 3-month mortality');
});

test('MELD interprets extremely high risk correctly', function () {
    $calculator = new MeldCalculator();

    // MELD ≥ 40 = Extremely high risk (capped at 40)
    $labs = createLabsWithMeld(10.0, 4.0, 3.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(40.0); // Maximum MELD score
    expect($result->interpretation)->toBe('Extremely high risk - >76% 3-month mortality');
});

test('MELD applies minimum value constraints correctly', function () {
    $calculator = new MeldCalculator();

    // Test with values below minimum (should be adjusted to 1.0)
    $labs = createLabsWithMeld(0.5, 0.8, 0.9);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(6.0); // Minimum MELD score after constraints
    expect($result->usedValues)->toBe([
        'Total Bilirubin' => 1.0, // Adjusted from 0.5
        'Creatinine' => 1.0,      // Adjusted from 0.8
        'INR' => 1.0,             // Adjusted from 0.9
    ]);
});

test('MELD caps creatinine at 4.0', function () {
    $calculator = new MeldCalculator();

    // Test with very high creatinine (should be capped at 4.0)
    $labs = createLabsWithMeld(2.0, 8.0, 1.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues['Creatinine'])->toBe(4.0); // Capped from 8.0
});

test('MELD caps final score between 6 and 40', function () {
    $calculator = new MeldCalculator();

    // Test minimum score
    $labs = createLabsWithMeld(1.0, 1.0, 1.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBeGreaterThanOrEqual(6);

    // Test maximum score
    $labs = createLabsWithMeld(50.0, 4.0, 5.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBeLessThanOrEqual(40);
});

test('MELD returns null when required values are missing', function () {
    $calculator = new MeldCalculator();

    // Missing INR
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
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('MELD handles invalid values correctly', function () {
    $calculator = new MeldCalculator();

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
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('MELD protects against invalid logarithm values', function () {
    $calculator = new MeldCalculator();

    // Test with zero/negative values that would cause log issues
    $labs = collect([
        [
            'name' => 'BILIRUBIN,TOTAL,Blood',
            'result' => '0', // Zero bilirubin
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

test('MELD used values match input values after constraints', function () {
    $calculator = new MeldCalculator();

    $labs = createLabsWithMeld(2.5, 1.8, 1.6);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toBe([
        'Total Bilirubin' => 2.5,
        'Creatinine' => 1.8,
        'INR' => 1.6,
    ]);
});
