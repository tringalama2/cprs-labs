<?php

use App\Services\Calculators\Calculators\FractionalExcretionSodiumCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithFena(float $targetFena): Collection
{
    // FENa = 100 × (SCr × UNa) / (SNa × UCr)
    // Rearranging: UNa = (FENa × SNa × UCr) / (100 × SCr)
    $serumCreatinine = 1.5;
    $serumSodium = 140;
    $urineCreatinine = 50;
    $urineSodium = ($targetFena * $serumSodium * $urineCreatinine) / (100 * $serumCreatinine);

    $testDate = Carbon::now();

    return collect([
        [
            'name' => 'CREATININE,blood',
            'result' => (string) $serumCreatinine,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => (string) $serumSodium,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => (string) $urineCreatinine,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'SODIUM,Urine',
            'result' => (string) $urineSodium,
            'collection_date' => $testDate,
        ],
    ]);
}

test('FENa calculator has correct required fields', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    $expectedFields = [
        'CREATININE,blood',
        'SODIUM,Blood',
        'CREATININE,Urine',
        'SODIUM,Urine',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test('FENa calculator has correct properties', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    expect($calculator->getName())->toBe('fena');
    expect($calculator->getDisplayName())->toBe('Fractional Excretion of Sodium (FENa)');
    expect($calculator->getUnits())->toBe('%');
    expect($calculator->getFormulaText())->toBe('100 × (SCr × UNa) / (SNa × UCr)');
    expect($calculator->getPriority())->toBe(1);
});

test('FENa calculation formula is correct', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // Test case: FENa = 100 × (1.5 × 80) / (140 × 50) = 1.71%
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => '140',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Urine',
            'result' => '80',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(1.71);
    expect($result->units)->toBe('%');
    expect($result->interpretation)->toBe('Intermediate range - clinical correlation needed');
});

test('FENa interprets pre-renal azotemia correctly', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // FENa < 1% = Pre-renal azotemia
    $labs = createLabsWithFena(0.8);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Pre-renal azotemia likely');
});

test('FENa interprets ATN correctly', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // FENa >= 2% = Acute tubular necrosis
    $labs = createLabsWithFena(2.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Acute tubular necrosis likely');
});

test('FENa interprets intermediate range correctly', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // FENa between 1-2% = Intermediate range
    $labs = createLabsWithFena(1.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Intermediate range - clinical correlation needed');
});

test('FENa returns null when required values are missing', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // Missing urine sodium
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => '140',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('FENa handles invalid values correctly', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // Test with out-of-range values
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '25', // Too high (max 20)
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => '140',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Urine',
            'result' => '80',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('FENa protects against division by zero', function () {
    $calculator = new FractionalExcretionSodiumCalculator();

    // Test with zero values that would cause division by zero
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Blood',
            'result' => '0', // Zero sodium
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'SODIUM,Urine',
            'result' => '80',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});
