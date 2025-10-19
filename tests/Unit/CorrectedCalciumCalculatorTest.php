<?php

use App\Services\Calculators\Calculators\CorrectedCalciumCalculator;
use App\Services\Calculators\Core\LabValueResolver;

beforeEach(function () {
    $this->calculator = new CorrectedCalciumCalculator();
});

test('Corrected Calcium calculator has correct required fields', function () {
    expect($this->calculator->getRequiredFields())->toBe([
        'CALCIUM,Blood',
    ]);
});

test('Corrected Calcium calculation formula is correct', function () {
    // Test case: Calcium = 8.0, Albumin = 3.0
    // Corrected Calcium = 8.0 + 0.8 × (4.0 - 3.0) = 8.0 + 0.8 = 8.8
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 8.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 3.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(8.8)
        ->and($result->units)->toBe('mg/dL');
});

test('Corrected Calcium with normal albumin shows no correction', function () {
    // Test case: Calcium = 9.5, Albumin = 4.0 (normal)
    // Corrected Calcium = 9.5 + 0.8 × (4.0 - 4.0) = 9.5 + 0 = 9.5
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 9.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 4.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.5)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');
});

test('Corrected Calcium with high albumin decreases calcium', function () {
    // Test case: Calcium = 10.0, Albumin = 5.0 (high)
    // Corrected Calcium = 10.0 + 0.8 × (4.0 - 5.0) = 10.0 - 0.8 = 9.2
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 10.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 5.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.2);
});

test('Corrected Calcium works with space in albumin field name', function () {
    // Test with 'ALBUMIN ,Blood' (note the space)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 8.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN ,Blood', 'result' => 2.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 8.5 + 0.8 × (4.0 - 2.5) = 8.5 + 1.2 = 9.7

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.7);
});

test('Corrected Calcium interprets hypocalcemia correctly', function () {
    // Test corrected calcium < 8.5
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 7.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 4.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(7.5)
        ->and($result->interpretation)->toBe('Hypocalcemia (corrected calcium < 8.5 mg/dL)')
        ->and($result->color)->toBe('red-500');
});

test('Corrected Calcium interprets normal calcium correctly', function () {
    // Test corrected calcium 8.5-10.5
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 9.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 3.8, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 9.0 + 0.8 × (4.0 - 3.8) = 9.0 + 0.16 = 9.16 ≈ 9.2

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.2)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)')
        ->and($result->color)->toBe('green-500');
});

test('Corrected Calcium interprets hypercalcemia correctly', function () {
    // Test corrected calcium > 10.5
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 11.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 4.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(11.0)
        ->and($result->interpretation)->toBe('Hypercalcemia (corrected calcium > 10.5 mg/dL)')
        ->and($result->color)->toBe('red-500');
});

test('Corrected Calcium boundary values work correctly', function () {
    // Test exactly 8.5 (should be normal)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 8.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 4.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(8.5)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');

    // Test exactly 10.5 (should be normal)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 10.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 4.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(10.5)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');

    // Test just above 10.5 (should be hypercalcemia)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 10.6, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 4.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(10.6)
        ->and($result->interpretation)->toBe('Hypercalcemia (corrected calcium > 10.5 mg/dL)');
});

test('Corrected Calcium returns null when required values are missing', function () {
    // Missing calcium
    $labs = collect([
        ['name' => 'ALBUMIN,Blood', 'result' => 3.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Missing albumin
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 9.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Both missing
    $labs = collect([]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();
});

test('Corrected Calcium handles invalid values correctly', function () {
    // Invalid calcium (too high)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 20.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 3.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Invalid calcium (too low)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 2.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 3.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Invalid albumin (too high)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 9.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 8.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Invalid albumin (too low)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 9.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 0.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();
});

test('Corrected Calcium used values and dates are correct', function () {
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 8.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 3.2, 'collection_date' => '2023-01-02'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->usedValues)->toBe([
            'Measured Calcium' => 8.5,
            'Serum Albumin' => 3.2,
        ])
        ->and($result->usedValueDates)->toBe([
            'Measured Calcium' => '2023-01-01',
            'Serum Albumin' => '2023-01-02',
        ]);
});

test('Corrected Calcium clinical scenarios work correctly', function () {
    // Hypoalbuminemia with normal measured calcium (should increase corrected calcium)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 8.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 2.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 8.0 + 0.8 × (4.0 - 2.0) = 8.0 + 1.6 = 9.6

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.6)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');

    // Severe hypoalbuminemia (malnutrition)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 7.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 1.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 7.5 + 0.8 × (4.0 - 1.5) = 7.5 + 2.0 = 9.5

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.5)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');

    // High albumin scenario (dehydration)
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 10.8, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 5.5, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 10.8 + 0.8 × (4.0 - 5.5) = 10.8 - 1.2 = 9.6

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.6)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');
});

test('Corrected Calcium precision and rounding work correctly', function () {
    // Test that values are rounded to 1 decimal place
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 8.33, 'collection_date' => '2023-01-01'],
        ['name' => 'ALBUMIN,Blood', 'result' => 3.17, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 8.33 + 0.8 × (4.0 - 3.17) = 8.33 + 0.664 = 8.994 ≈ 9.0

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.0); // 4.0 - 3.17 = 0.83 ≈ 0.8
});

test('Corrected Calcium demonstrates clinical importance', function () {
    // Show how correction can change interpretation

    // Case 1: Low measured calcium with low albumin - correction reveals normal calcium
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 7.8, 'collection_date' => '2023-01-01'], // Appears low
        ['name' => 'ALBUMIN,Blood', 'result' => 2.5, 'collection_date' => '2023-01-01'], // Low albumin
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 7.8 + 0.8 × (4.0 - 2.5) = 7.8 + 1.2 = 9.0 (normal)

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(9.0)
        ->and($result->interpretation)->toBe('Normal corrected calcium (8.5-10.5 mg/dL)');

    // Case 2: Normal measured calcium with high albumin - correction reveals hypocalcemia
    $labs = collect([
        ['name' => 'CALCIUM,Blood', 'result' => 9.0, 'collection_date' => '2023-01-01'], // Appears normal
        ['name' => 'ALBUMIN,Blood', 'result' => 5.0, 'collection_date' => '2023-01-01'], // High albumin
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // Corrected = 9.0 + 0.8 × (4.0 - 5.0) = 9.0 - 0.8 = 8.2 (low)

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(8.2)
        ->and($result->interpretation)->toBe('Hypocalcemia (corrected calcium < 8.5 mg/dL)');
});
