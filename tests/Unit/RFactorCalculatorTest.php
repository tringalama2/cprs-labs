<?php

use App\Services\Calculators\Calculators\RFactorCalculator;
use App\Services\Calculators\Core\LabValueResolver;

beforeEach(function () {
    $this->calculator = new RFactorCalculator();
});

it('R Factor calculator has correct required fields', function () {
    expect($this->calculator->getRequiredFields())->toBe([
        'ALT,Blood',
        'ALKP,Blood',
    ]);
});

it('R Factor calculation formula is correct', function () {
    // Test case: ALT = 200 (5x ULN), ALP = 240 (2x ULN)
    // R = (200/40) / (240/120) = 5 / 2 = 2.5 (Mixed pattern)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 200.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 240.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(2.5)
        ->and($result->units)->toBe('')
        ->and($result->usedValues['ALT/ULN'])->toBe(5.0)
        ->and($result->usedValues['ALP/ULN'])->toBe(2.0);
});

it('R Factor handles normal values correctly', function () {
    // R ≥ 5: Hepatocellular pattern
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 30.0, 'collection_date' => '2023-01-01'], // wnl
        ['name' => 'ALKP,Blood', 'result' => 80.0, 'collection_date' => '2023-01-01'], // wnl
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->toBeNull();
});

it('R Factor interprets hepatocellular pattern correctly', function () {
    // R ≥ 5: Hepatocellular pattern
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 400.0, 'collection_date' => '2023-01-01'], // 10x ULN
        ['name' => 'ALKP,Blood', 'result' => 180.0, 'collection_date' => '2023-01-01'], // 1.5x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    $rFactor = (400 / 40) / (180 / 120); // 10 / 1.5 = 6.67

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(6.67)
        ->and($result->interpretation)->toBe('Hepatocellular pattern (R ≥ 5)');
});

it('R Factor interprets mixed pattern correctly', function () {
    // 2 ≤ R < 5: Mixed pattern
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 120.0, 'collection_date' => '2023-01-01'], // 3x ULN
        ['name' => 'ALKP,Blood', 'result' => 180.0, 'collection_date' => '2023-01-01'], // 1.5x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    $rFactor = (120 / 40) / (180 / 120); // 3 / 1.5 = 2.0

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(2.0)
        ->and($result->interpretation)->toBe('Mixed pattern (2 ≤ R < 5)');
});

it('R Factor interprets cholestatic pattern correctly', function () {
    // R < 2: Cholestatic pattern
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 60.0, 'collection_date' => '2023-01-01'], // 1.5x ULN
        ['name' => 'ALKP,Blood', 'result' => 240.0, 'collection_date' => '2023-01-01'], // 2x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    $rFactor = (60 / 40) / (240 / 120); // 1.5 / 2 = 0.75

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(0.75)
        ->and($result->interpretation)->toBe('Cholestatic pattern (R < 2)');
});

it('R Factor boundary values work correctly', function () {
    // Test R = exactly 5.0 (should be hepatocellular)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 200.0, 'collection_date' => '2023-01-01'], // 5x ULN
        ['name' => 'ALKP,Blood', 'result' => 120.0, 'collection_date' => '2023-01-01'], // 1x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(5.0)
        ->and($result->interpretation)->toBe('Hepatocellular pattern (R ≥ 5)');

    // Test R = just under 5.0 (should be mixed)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 199.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 120.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(4.98)
        ->and($result->interpretation)->toBe('Mixed pattern (2 ≤ R < 5)');

    // Test R = just under 2.0 (should be cholestatic)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 79.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 120.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(1.98)
        ->and($result->interpretation)->toBe('Cholestatic pattern (R < 2)');
});

it('R Factor returns null when required values are missing', function () {
    // Missing ALT
    $labs = collect([
        ['name' => 'ALKP,Blood', 'result' => 150.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Missing ALP
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 100.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Both missing
    $labs = collect([]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();
});

it('R Factor handles invalid values correctly', function () {
    // Invalid ALT (too high)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 6000.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 150.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Invalid ALT (too low)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 0.5, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 150.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Invalid ALP (too high)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 100.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 2500.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();

    // Invalid ALP (too low)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 100.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 5.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();
});

it('R Factor protects against division by zero', function () {
    // ALP = 0 should return null
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 100.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 0.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    expect($result)->toBeNull();
});

it('R Factor used values and dates are correct', function () {
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 160.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 180.0, 'collection_date' => '2023-01-02'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));

    expect($result)->not()->toBeNull()
        ->and($result->usedValues)->toBe([
            'ALT' => 160.0,
            'Alkaline Phosphatase' => 180.0,
            'ALT/ULN' => 4.0,
            'ALP/ULN' => 1.5,
        ])
        ->and($result->usedValueDates)->toBe([
            'ALT' => '2023-01-01',
            'Alkaline Phosphatase' => '2023-01-02',
        ]);
});

it('R Factor clinical scenarios work correctly', function () {
    // Drug-induced hepatitis (high ALT, normal ALP)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 800.0, 'collection_date' => '2023-01-01'], // 20x ULN
        ['name' => 'ALKP,Blood', 'result' => 100.0, 'collection_date' => '2023-01-01'], // 0.83x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    $expectedR = (800 / 40) / (100 / 120); // 20 / 0.83 = 24.0

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(24.0)
        ->and($result->interpretation)->toBe('Hepatocellular pattern (R ≥ 5)');

    // Drug-induced cholestasis (normal ALT, high ALP)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 50.0, 'collection_date' => '2023-01-01'], // 1.25x ULN
        ['name' => 'ALKP,Blood', 'result' => 600.0, 'collection_date' => '2023-01-01'], // 5x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    $expectedR = (50 / 40) / (600 / 120); // 1.25 / 5 = 0.25

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(0.25)
        ->and($result->interpretation)->toBe('Cholestatic pattern (R < 2)');

    // Mixed injury (both elevated proportionally)
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 200.0, 'collection_date' => '2023-01-01'], // 5x ULN
        ['name' => 'ALKP,Blood', 'result' => 360.0, 'collection_date' => '2023-01-01'], // 3x ULN
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    $expectedR = (200 / 40) / (360 / 120); // 5 / 3 = 1.67

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(1.67)
        ->and($result->interpretation)->toBe('Cholestatic pattern (R < 2)');
});

it('R Factor precision and rounding work correctly', function () {
    // Test that values are rounded to 2 decimal places
    $labs = collect([
        ['name' => 'ALT,Blood', 'result' => 133.0, 'collection_date' => '2023-01-01'],
        ['name' => 'ALKP,Blood', 'result' => 127.0, 'collection_date' => '2023-01-01'],
    ]);

    $result = $this->calculator->calculate(new LabValueResolver($labs));
    // R = (133/40) / (127/120) = 3.325 / 1.058333 = 3.143...

    expect($result)->not()->toBeNull()
        ->and($result->value)->toBe(3.14)
        ->and($result->usedValues['ALT/ULN'])->toBe(3.33)
        ->and($result->usedValues['ALP/ULN'])->toBe(1.06);
});
