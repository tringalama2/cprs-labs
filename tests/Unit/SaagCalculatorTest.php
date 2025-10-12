<?php

use App\Services\Calculators\Calculators\SaagCalculator;
use App\Services\Calculators\Core\LabValueResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createLabsWithSaag(float $serumAlbumin, float $asciticAlbumin, ?float $asciticProtein = null, bool $useSpaceInAlbuminName = false): Collection
{
    $testDate = Carbon::now();

    $albumin_field = $useSpaceInAlbuminName ? 'ALBUMIN ,Blood' : 'ALBUMIN,Blood';

    $labs = collect([
        [
            'name' => $albumin_field,
            'result' => (string) $serumAlbumin,
            'collection_date' => $testDate,
        ],
        [
            'name' => 'ALBUMIN,PERITONEAL FLUID',
            'result' => (string) $asciticAlbumin,
            'collection_date' => $testDate,
        ],
    ]);

    if ($asciticProtein !== null) {
        $labs->push([
            'name' => 'PROTEIN,PERITONEAL FLUID',
            'result' => (string) $asciticProtein,
            'collection_date' => $testDate,
        ]);
    }

    return $labs;
}

test('SAAG calculator has correct required fields', function () {
    $calculator = new SaagCalculator();

    $expectedFields = [
        'ALBUMIN,PERITONEAL FLUID',
    ];

    expect($calculator->getRequiredFields())->toBe($expectedFields);
});

test('SAAG calculation formula is correct', function () {
    $calculator = new SaagCalculator();

    // Test case: SAAG = 3.5 - 1.2 = 2.3
    $labs = createLabsWithSaag(3.5, 1.2);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(2.3);
    expect($result->units)->toBe('g/dL');
    expect($result->interpretation)->toBe('Portal hypertension (SAAG ≥ 1.1)');
});

test('SAAG works with space in serum albumin field name', function () {
    $calculator = new SaagCalculator();

    // Test with "ALBUMIN ,Blood" (with space)
    $labs = createLabsWithSaag(3.5, 1.2, null, true);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(2.3);
    expect($result->usedValues['Serum Albumin'])->toBe(3.5);
});

test('SAAG interprets portal hypertension correctly', function () {
    $calculator = new SaagCalculator();

    // SAAG ≥ 1.1 = Portal hypertension
    $labs = createLabsWithSaag(3.5, 1.2);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(2.3);
    expect($result->interpretation)->toBe('Portal hypertension (SAAG ≥ 1.1)');
});

test('SAAG interprets non-portal hypertension correctly', function () {
    $calculator = new SaagCalculator();

    // SAAG < 1.1 = Non-portal hypertension
    $labs = createLabsWithSaag(3.0, 2.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(0.5);
    expect($result->interpretation)->toBe('Non-portal hypertension etiology (SAAG < 1.1)');
});

test('SAAG interprets cardiac ascites with protein', function () {
    $calculator = new SaagCalculator();

    // SAAG ≥ 1.1 and Protein ≥ 2.5 = Cardiac ascites
    $labs = createLabsWithSaag(3.5, 1.0, 3.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(2.5);
    expect($result->interpretation)->toBe('Cardiac ascites (SAAG ≥ 1.1, Ascitic protein ≥ 2.5)');
    expect($result->usedValues)->toHaveKey('Ascitic Protein');
    expect($result->usedValues['Ascitic Protein'])->toBe(3.0);
});

test('SAAG interprets cirrhosis with protein', function () {
    $calculator = new SaagCalculator();

    // SAAG ≥ 1.1 and Protein < 2.5 = Cirrhosis
    $labs = createLabsWithSaag(3.5, 1.0, 2.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(2.5);
    expect($result->interpretation)->toBe('Cirrhosis (SAAG ≥ 1.1, Ascitic protein < 2.5)');
});

test('SAAG interprets nephrotic ascites with protein', function () {
    $calculator = new SaagCalculator();

    // SAAG < 1.1 and Protein < 2.5 = Nephrotic ascites
    $labs = createLabsWithSaag(3.0, 2.5, 2.0);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(0.5);
    expect($result->interpretation)->toBe('Nephrotic syndrome (SAAG < 1.1, Ascitic protein < 2.5)');
});

test('SAAG interprets TB or malignant ascites with protein', function () {
    $calculator = new SaagCalculator();

    // SAAG < 1.1 and Protein > 2.5 = TB or Malignant ascites
    $labs = createLabsWithSaag(3.0, 2.5, 3.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(0.5);
    expect($result->interpretation)->toBe('TB or Malignancy (SAAG < 1.1, Ascitic protein > 2.5)');
});

test('SAAG boundary values work correctly', function () {
    $calculator = new SaagCalculator();

    // Test boundary at SAAG = 1.1
    $labs = createLabsWithSaag(3.0, 1.9);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(1.1);
    expect($result->interpretation)->toBe('Portal hypertension (SAAG ≥ 1.1)');

    // Test boundary at Protein = 2.5 with high SAAG
    $labs = createLabsWithSaag(3.5, 1.0, 2.5);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->interpretation)->toBe('Cardiac ascites (SAAG ≥ 1.1, Ascitic protein ≥ 2.5)');
});

test('SAAG returns null when required values are missing', function () {
    $calculator = new SaagCalculator();

    // Missing ascitic albumin
    $labs = collect([
        [
            'name' => 'ALBUMIN,Blood',
            'result' => '3.5',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('SAAG returns null when serum albumin is missing', function () {
    $calculator = new SaagCalculator();

    // Missing serum albumin (neither variant)
    $labs = collect([
        [
            'name' => 'ALBUMIN,PERITONEAL FLUID',
            'result' => '1.2',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('SAAG handles invalid values correctly', function () {
    $calculator = new SaagCalculator();

    // Test with out-of-range values
    $labs = collect([
        [
            'name' => 'ALBUMIN,Blood',
            'result' => '10', // Too high (max 6.0)
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'ALBUMIN,PERITONEAL FLUID',
            'result' => '1.2',
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->toBeNull();
});

test('SAAG handles invalid protein values gracefully', function () {
    $calculator = new SaagCalculator();

    // Test with valid SAAG values but invalid protein (should fall back to basic interpretation)
    $labs = collect([
        [
            'name' => 'ALBUMIN,Blood',
            'result' => '3.5',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'ALBUMIN,PERITONEAL FLUID',
            'result' => '1.2',
            'collection_date' => Carbon::now(),
        ],
        [
            'name' => 'PROTEIN,PERITONEAL FLUID',
            'result' => '15', // Too high (max 10.0)
            'collection_date' => Carbon::now(),
        ],
    ]);

    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->value)->toBe(2.3);
    expect($result->interpretation)->toBe('Portal hypertension (SAAG ≥ 1.1)'); // Basic interpretation
    expect($result->usedValues)->not->toHaveKey('Ascitic Protein'); // Protein not included due to invalid value
});

test('SAAG used values and dates are correct', function () {
    $calculator = new SaagCalculator();

    $labs = createLabsWithSaag(3.5, 1.2, 2.8);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toBe([
        'Serum Albumin' => 3.5,
        'Ascitic Albumin' => 1.2,
        'Ascitic Protein' => 2.8,
    ]);
    expect($result->usedValueDates)->toHaveKeys([
        'Serum Albumin',
        'Ascitic Albumin',
        'Ascitic Protein',
    ]);
});

test('SAAG used values without protein are correct', function () {
    $calculator = new SaagCalculator();

    $labs = createLabsWithSaag(3.5, 1.2);
    $resolver = new LabValueResolver($labs);
    $result = $calculator->calculate($resolver);

    expect($result)->not->toBeNull();
    expect($result->usedValues)->toBe([
        'Serum Albumin' => 3.5,
        'Ascitic Albumin' => 1.2,
    ]);
    expect($result->usedValueDates)->toHaveKeys([
        'Serum Albumin',
        'Ascitic Albumin',
    ]);
    expect($result->usedValues)->not->toHaveKey('Ascitic Protein');
});
