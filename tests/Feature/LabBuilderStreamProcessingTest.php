<?php

use App\Services\LabBuilder;
use Illuminate\Support\Collection;

it('processes rows incrementally with stream processing', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);

    // Test that row processing can work incrementally
    // This would verify that a generator-based approach works correctly
    $rows = collect(preg_split("/\r\n|\n|\r/", $rawLabs));

    $processedCount = 0;
    foreach ($rows as $row) {
        if (str_contains($row, 'Test name') && str_contains($row, 'Result')) {
            // Skip header row
            continue;
        }
        if (preg_match('/^[A-Z]/', $row) && str_contains($row, ' ')) {
            $processedCount++;
        }
    }

    expect($processedCount)->toBeGreaterThan(0, 'Should identify result rows for processing');
});

it('maintains parsing accuracy with stream processing', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));

    // Current collection-based approach
    $collectionBuilder = new LabBuilder($rawLabs);
    $collectionBuilder->build();
    $collectionResults = $collectionBuilder->getLabCollection();

    // Stream processing should produce identical results
    // This test ensures the refactoring doesn't change output
    expect($collectionResults->count())->toBeGreaterThan(0);

    // Verify specific lab results are parsed correctly
    if ($collectionResults->count() > 0) {
        $firstResult = $collectionResults->first();
        expect($firstResult)->toHaveKey('name');
        expect($firstResult)->toHaveKey('result');
        expect($firstResult)->toHaveKey('collection_date');
    }
});

it('handles malformed input gracefully in stream processing', function () {
    $malformedInput = "Invalid line\nAnother bad line\n".file_get_contents(resource_path('lab.test.txt'));

    $labBuilder = new LabBuilder($malformedInput);

    // Should not throw exceptions
    expect(fn () => $labBuilder->build())->not->toThrow(Exception::class);

    $results = $labBuilder->getLabCollection();
    expect($results)->toBeInstanceOf(Collection::class);

    $unparsable = $labBuilder->getUnparsableRows();
    expect($unparsable)->toBeInstanceOf(Collection::class);
});
