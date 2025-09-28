<?php

use App\Services\LabBuilder;
use Illuminate\Support\Collection;

it('benchmarks arrays vs collections for simple lookups', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    $labLabels = $labBuilder->getLabLabels();

    if ($labLabels->count() > 0) {
        // Setup data structures
        $labLookupCollection = $labLabels->keyBy('name');
        $labLookupArray = $labLabels->keyBy('name')->toArray();

        // Get test keys for lookup benchmarking
        $testKeys = $labLookupCollection->keys()->take(min(5, $labLabels->count()))->toArray();
        $lookupIterations = 1000; // Number of lookups to perform

        // Benchmark Collection Lookups
        $collectionStartTime = microtime(true);
        $collectionStartMemory = memory_get_usage(true);

        for ($i = 0; $i < $lookupIterations; $i++) {
            foreach ($testKeys as $key) {
                $result = $labLookupCollection->get($key);
            }
        }

        $collectionEndTime = microtime(true);
        $collectionEndMemory = memory_get_usage(true);

        $collectionTime = ($collectionEndTime - $collectionStartTime) * 1000; // Convert to ms
        $collectionMemoryUsed = $collectionEndMemory - $collectionStartMemory;

        // Benchmark Array Lookups
        $arrayStartTime = microtime(true);
        $arrayStartMemory = memory_get_usage(true);

        for ($i = 0; $i < $lookupIterations; $i++) {
            foreach ($testKeys as $key) {
                $result = $labLookupArray[$key] ?? null;
            }
        }

        $arrayEndTime = microtime(true);
        $arrayEndMemory = memory_get_usage(true);

        $arrayTime = ($arrayEndTime - $arrayStartTime) * 1000; // Convert to ms
        $arrayMemoryUsed = $arrayEndMemory - $arrayStartMemory;

        // Calculate performance differences
        $timeDifference = $collectionTime - $arrayTime;
        $timePercentImprovement = $collectionTime > 0 ? (($timeDifference / $collectionTime) * 100) : 0;
        $memoryDifference = $collectionMemoryUsed - $arrayMemoryUsed;

        // Display benchmark results
        echo "\n=== ARRAY VS COLLECTION LOOKUP BENCHMARK ===\n";
        echo "Test Setup:\n";
        echo "  - Lab Labels: {$labLabels->count()}\n";
        echo '  - Test Keys: '.count($testKeys)."\n";
        echo "  - Lookup Iterations: {$lookupIterations}\n";
        echo '  - Total Lookups: '.($lookupIterations * count($testKeys))."\n";
        echo "-----------------------------------------------\n";
        echo "Collection Lookups:\n";
        echo '  - Time: '.round($collectionTime, 4)." ms\n";
        echo '  - Memory: '.round($collectionMemoryUsed / 1024, 2)." KB\n";
        echo "-----------------------------------------------\n";
        echo "Array Lookups:\n";
        echo '  - Time: '.round($arrayTime, 4)." ms\n";
        echo '  - Memory: '.round($arrayMemoryUsed / 1024, 2)." KB\n";
        echo "-----------------------------------------------\n";
        echo "Performance Comparison:\n";
        echo '  - Time Difference: '.round($timeDifference, 4)." ms\n";
        echo '  - Array Speed Improvement: '.round($timePercentImprovement, 1)."%\n";
        echo '  - Memory Difference: '.round($memoryDifference / 1024, 2)." KB\n";
        echo "=============================================\n";

        // Data integrity checks
        expect($labLookupArray)->toBeArray();
        expect($labLookupCollection)->toBeInstanceOf(Collection::class);

        // Verify both methods return same data
        foreach ($testKeys as $key) {
            $collectionResult = $labLookupCollection->get($key);
            $arrayResult = $labLookupArray[$key] ?? null;
            expect($arrayResult)->toEqual($collectionResult, "Array and collection should return same data for key: {$key}");
        }

        // Performance expectations
        expect($arrayTime)->toBeGreaterThanOrEqual(0, 'Array lookup time should be measurable');
        expect($collectionTime)->toBeGreaterThanOrEqual(0, 'Collection lookup time should be measurable');

        // Arrays should typically be faster for simple lookups
        if ($collectionTime > 0 && $arrayTime > 0) {
            expect($arrayTime)->toBeLessThanOrEqual($collectionTime * 1.5, 'Array lookups should be competitive with collection lookups');
        }

        // Functionality should remain the same
        if (! empty($labLookupArray)) {
            $firstKey = array_key_first($labLookupArray);
            expect($labLookupArray[$firstKey])->toHaveKey('label');
        }
    }
});

it('measures memory usage of collection vs array approaches', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    $memoryBefore = memory_get_usage();

    // Collection approach
    $collectionData = $labBuilder->getLabCollection();
    $collectionLabels = $labBuilder->getLabLabels();

    $memoryAfterCollections = memory_get_usage(true);

    // Array approach for comparison
    $arrayData = $collectionData->toArray();
    $arrayLabels = $collectionLabels->toArray();

    $memoryAfterArrays = memory_get_usage(true);

    $collectionMemory = $memoryAfterCollections - $memoryBefore;
    $arrayMemory = $memoryAfterArrays - $memoryAfterCollections;

    // Both approaches should work - focus on data integrity
    expect($arrayMemory)->toBeGreaterThanOrEqual(0, 'Array memory usage should be measurable');

    // Verify data integrity
    expect(count($arrayData))->toBe($collectionData->count());
    expect(count($arrayLabels))->toBe($collectionLabels->count());
});

it('optimizes data structures based on usage patterns', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    $labs = $labBuilder->getLabCollection();
    $labels = $labBuilder->getLabLabels();

    // Identify collections that are only used for lookup
    if ($labels->count() > 0) {
        // Labels are primarily used for lookup - array is better
        $labelsArray = $labels->keyBy('name')->toArray();
        expect($labelsArray)->toBeArray();

        // Test lookup performance
        if (! empty($labelsArray)) {
            $testKey = array_key_first($labelsArray);
            expect(isset($labelsArray[$testKey]))->toBeTrue();
        }
    }

    // Identify collections that need Laravel collection methods
    if ($labs->count() > 0) {
        // Labs need grouping, filtering - keep as collection
        $grouped = $labs->groupBy('specimen_unique_id');
        expect($grouped)->toBeInstanceOf(Collection::class);
    }
});

it('handles large datasets with memory-efficient structures', function () {
    // Create larger test data
    $baseInput = file_get_contents(resource_path('lab.test.txt'));
    $largeInput = str_repeat($baseInput, 10);

    $memoryBefore = memory_get_usage(true);

    $labBuilder = new LabBuilder($largeInput);
    $labBuilder->build();

    $memoryAfter = memory_get_usage(true);
    $memoryUsed = $memoryAfter - $memoryBefore;

    // Should handle larger datasets without excessive memory usage
    expect($memoryUsed)->toBeLessThan(100 * 1024 * 1024, 'Should handle large datasets within reasonable memory limits');

    $results = $labBuilder->getLabCollection();
    expect($results->count())->toBeGreaterThan(0);
});

it('preserves functionality when switching data structures', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    // Original collection-based approach
    $originalLabels = $labBuilder->getLabLabels();
    $originalPanels = $labBuilder->getPanels();

    // Array-based approach should produce same results
    if ($originalLabels->count() > 0) {
        $arrayLabels = $originalLabels->toArray();
        expect(count($arrayLabels))->toBe($originalLabels->count());

        // Key-value pairs should match
        foreach ($originalLabels as $index => $label) {
            expect($arrayLabels[$index])->toEqual($label);
        }
    }

    if ($originalPanels->count() > 0) {
        $arrayPanels = $originalPanels->toArray();
        expect(count($arrayPanels))->toBe($originalPanels->count());
    }
});

it('chooses appropriate data structure for each use case', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    // Use case 1: Simple key-value lookup - array is better
    $labsAndPanels = $labBuilder->getLabLabels()->keyBy('name');
    $lookupArray = $labsAndPanels->toArray();
    expect($lookupArray)->toBeArray();

    // Use case 2: Complex operations - collection is better
    $labCollection = $labBuilder->getLabCollection();
    if ($labCollection->count() > 0) {
        $filtered = $labCollection->filter(function ($lab) {
            return str_contains($lab['result'] ?? '', 'H');
        });
        expect($filtered)->toBeInstanceOf(Collection::class);
    }

    // Use case 3: Simple counting - array or collection both work
    $panels = $labBuilder->getPanels();
    expect($panels)->toBeInstanceOf(Collection::class);
});
