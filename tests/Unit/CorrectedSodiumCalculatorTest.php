<?php

use App\Services\Calculators\Calculators\CorrectedSodiumCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithCorrectedSodium(float $sodium, float $glucose): Collection
{
    $testDate = Carbon::now();

    return collect([
        [
            'name' => 'SODIUM,Blood',
            'result' => (string) $sodium,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'GLUCOSE,Blood',
            'result' => (string) $glucose,
            'collection_date' => $testDate,
        ],
    ]);
}

test('Corrected Sodium calculator has correct required fields', function () {
    $calculator = new CorrectedSodiumCalculator();

    $expectedFields = [
        'SODIUM,Blood',
        'GLUCOSE,Blood',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test('Corrected Sodium calculation returns null if glucose < 200', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Test case: Normal glucose (100) should not change sodium
    // Both formulas: 130 + 1.6 × 0 = 130, 130 + 2.4 × 0 = 130
    $labs = createLabsWithCorrectedSodium(130, 100);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('Corrected Sodium calculation with hyperglycemia shows range', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Test case: High glucose should show range
    // Formula 1: 130 + 1.6 × [(300 - 100) / 100] = 130 + 3.2 = 133.2
    // Formula 2: 130 + 2.4 × [(300 - 100) / 100] = 130 + 4.8 = 134.8
    $labs = createLabsWithCorrectedSodium(130, 300);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('133 - 135');
    expect($result->interpretation)->toBe('Hyponatremia (corrected sodium range < 135 mEq/L)');
});

test('Corrected Sodium calculation with severe hyperglycemia', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Test case: Very high glucose
    // Formula 1: 125 + 1.6 × [(500 - 100) / 100] = 125 + 6.4 = 131.4
    // Formula 2: 125 + 2.4 × [(500 - 100) / 100] = 125 + 9.6 = 134.6
    $labs = createLabsWithCorrectedSodium(125, 500);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('131 - 135');
    expect($result->interpretation)->toBe('Hyponatremia (corrected sodium range < 135 mEq/L)');
});

test('Corrected Sodium interprets hyponatremia correctly', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Both corrections still result in hyponatremia
    $labs = createLabsWithCorrectedSodium(130, 200);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('132');
    expect($result->interpretation)->toBe('Hyponatremia (corrected sodium range < 135 mEq/L)');
});

test('Corrected Sodium interprets normal sodium correctly', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Normal sodium range
    $labs = createLabsWithCorrectedSodium(140, 200);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('142');
    expect($result->interpretation)->toBe('Normal corrected sodium range (135-145 mEq/L)');
});

test('Corrected Sodium interprets hypernatremia correctly', function () {
    $calculator = new CorrectedSodiumCalculator();

    // High sodium
    $labs = createLabsWithCorrectedSodium(144, 260);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('147 - 148');
    expect($result->interpretation)->toBe('Hypernatremia (corrected sodium range > 145 mEq/L)');
});

test('Corrected Sodium boundary values work correctly', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Test boundary at 135 mEq/L
    $labs = createLabsWithCorrectedSodium(135, 200);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe('137');
    expect($result->interpretation)->toBe('Normal corrected sodium range (135-145 mEq/L)');

    // Test boundary at 145 mEq/L
    $labs = createLabsWithCorrectedSodium(145, 200);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result->value)->toBe('147');
    expect($result->interpretation)->toBe('Hypernatremia (corrected sodium range > 145 mEq/L)');
});

test('Corrected Sodium shows hyperglycemia correction range', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Compare measured vs corrected sodium with hyperglycemia
    $labs = createLabsWithCorrectedSodium(128, 400); // Severe hyperglycemia
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    // Formula 1: 128 + 1.6 × 3 = 132.8, Formula 2: 128 + 2.4 × 3 = 135.2
    expect($result->value)->toBe('133 - 135');
    expect($result->usedValues['Measured Sodium'])->toBe(128.0);
    expect($result->usedValues['Glucose'])->toBe(400.0);
    expect($result->interpretation)->toBe('Hyponatremia (corrected sodium range < 135 mEq/L)');
});

test('Corrected Sodium returns null when required values are missing', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Missing glucose
    $labs = collect([
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

test('Corrected Sodium returns null when sodium is missing', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Missing sodium
    $labs = collect([
        [
            'name' => 'GLUCOSE,Blood',
            'result' => '150',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('Corrected Sodium handles invalid sodium values correctly', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Test with out-of-range sodium (too high)
    $labs = collect([
        [
            'name' => 'SODIUM,Blood',
            'result' => '250', // Too high (max 200)
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'GLUCOSE,Blood',
            'result' => '150',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('Corrected Sodium handles invalid glucose values correctly', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Test with out-of-range glucose (too high)
    $labs = collect([
        [
            'name' => 'SODIUM,Blood',
            'result' => '140',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'GLUCOSE,Blood',
            'result' => '1000', // Too high (max 800)
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('Corrected Sodium used values include both calculations', function () {
    $calculator = new CorrectedSodiumCalculator();

    $labs = createLabsWithCorrectedSodium(135, 200);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toBe([
        'Measured Sodium' => 135.0,
        'Glucose' => 200.0,
    ]);
    expect($result->usedValueDates)->toHaveKeys([
        'Measured Sodium',
        'Glucose',
    ]);
});

test('Corrected Sodium demonstrates clinical range significance', function () {
    $calculator = new CorrectedSodiumCalculator();

    // Clinical scenario: Range can cross normal boundary
    $labs = createLabsWithCorrectedSodium(132, 250);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    // Formula 1: 132 + 1.6 × 1.5 = 134.4, Formula 2: 132 + 2.4 × 1.5 = 135.6
    expect($result->value)->toBe('134 - 136');

    // The range crosses the boundary but interpretation uses average (135.0)
    expect($result->interpretation)->toBe('Normal corrected sodium range (135-145 mEq/L)');
});
