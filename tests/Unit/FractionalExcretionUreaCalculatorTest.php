<?php

use App\Services\Calculators\Calculators\FractionalExcretionUreaCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithFEUrea(float $targetFEUrea): Collection
{
    // FEUrea = 100 × (SCr × UUrea) / (SUrea × UCr)
    // Rearranging: UUrea = (FEUrea × SUrea × UCr) / (100 × SCr)
    $serumCreatinine = 1.5;
    $serumUrea = 20;
    $urineCreatinine = 50;
    $urineUrea = ($targetFEUrea * $serumUrea * $urineCreatinine) / (100 * $serumCreatinine);

    $testDate = Carbon::now();

    return collect([
        [
            'name' => 'CREATININE,blood',
            'result' => (string) $serumCreatinine,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'UREA NITROGEN,Blood',
            'result' => (string) $serumUrea,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => (string) $urineCreatinine,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'UREA NITROGEN,Urine',
            'result' => (string) $urineUrea,
            'collection_date' => $testDate,
        ],
    ]);
}

test('FEUrea calculator has correct required fields', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    $expectedFields = [
        'CREATININE,blood',
        'UREA NITROGEN,Blood',
        'CREATININE,Urine',
        'UREA NITROGEN,Urine',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test('FEUrea calculator has correct properties', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    expect($calculator->getName())->toBe('feurea');
    expect($calculator->getDisplayName())->toBe('Fractional Excretion of Urea (FEUrea)');
    expect($calculator->getUnits())->toBe('%');
    expect($calculator->getFormulaText())->toBe('100 × (SCr × UUrea) / (SUrea × UCr)');
    expect($calculator->getPriority())->toBe(2);
});

test('FEUrea calculation formula is correct', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // Test case: FEUrea = 100 × (1.5 × 200) / (20 × 50) = 30%
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Blood',
            'result' => '20',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Urine',
            'result' => '200',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(30.0);
    expect($result->units)->toBe('%');
    expect($result->interpretation)->toBe('Pre-renal azotemia likely');
});

test('FEUrea interprets pre-renal azotemia correctly', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // FEUrea < 35% = Pre-renal azotemia
    $labs = createLabsWithFEUrea(25.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Pre-renal azotemia likely');
});

test('FEUrea interprets ATN correctly', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // FEUrea >= 50% = Acute tubular necrosis
    $labs = createLabsWithFEUrea(60.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Acute tubular necrosis likely');
});

test('FEUrea interprets intermediate range correctly', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // FEUrea between 35-50% = Intermediate range
    $labs = createLabsWithFEUrea(40.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Intermediate range - clinical correlation needed');
});

test('FEUrea returns null when required values are missing', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // Missing urine urea
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Blood',
            'result' => '20',
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

test('FEUrea handles invalid values correctly', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // Test with out-of-range values
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '25', // Too high (max 20)
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Blood',
            'result' => '20',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Urine',
            'result' => '200',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('FEUrea protects against division by zero', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // Test with zero values that would cause division by zero
    $labs = collect([
        [
            'name' => 'CREATININE,blood',
            'result' => '1.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Blood',
            'result' => '0', // Zero urea
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'CREATININE,Urine',
            'result' => '50',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'UREA NITROGEN,Urine',
            'result' => '200',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('FEUrea boundary values work correctly', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    // Test boundary at 35%
    $labs = createLabsWithFEUrea(35.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Intermediate range - clinical correlation needed');

    // Test boundary at 50%
    $labs = createLabsWithFEUrea(50.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Acute tubular necrosis likely');
});

test('FEUrea used values match input values', function () {
    $calculator = new FractionalExcretionUreaCalculator();

    $labs = createLabsWithFEUrea(30.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toHaveKeys([
        'Serum Creatinine',
        'Serum Urea Nitrogen',
        'Urine Creatinine',
        'Urine Urea Nitrogen',
    ]);
    expect($result->usedValueDates)->toHaveKeys([
        'Serum Creatinine',
        'Serum Urea Nitrogen',
        'Urine Creatinine',
        'Urine Urea Nitrogen',
    ]);
});
