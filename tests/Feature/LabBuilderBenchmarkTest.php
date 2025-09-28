<?php

use App\Services\LabBuilder;
use App\Services\MicroBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

it('benchmarks current LabBuilder performance with full lab.test.txt - 3 run average', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $results = [];

    // Run 3 benchmark iterations
    for ($i = 1; $i <= 3; $i++) {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        $startPeakMemory = memory_get_peak_usage(true);

        DB::enableQueryLog();
        DB::flushQueryLog();

        $labBuilder = new LabBuilder($rawLabs);
        $labBuilder->build();
        $labBuilder->sort(true);

        // Get all data that would be used in view
        $labs = $labBuilder->getLabCollection();
        $labLabels = $labBuilder->getLabLabels();
        $datetimeHeaders = $labBuilder->getDateTimeHeaders();
        $panels = $labBuilder->getPanels();
        $unparsableRows = $labBuilder->getUnparsableRows();

        // Simulate view operations
        $groupedLabs = $labs->groupBy('specimen_unique_id');
        $labsByName = $labs->groupBy('name');

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $endPeakMemory = memory_get_peak_usage(true);

        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsed = $endMemory - $startMemory;
        $peakMemoryUsed = $endPeakMemory - $startPeakMemory;

        $results[$i] = [
            'execution_time' => $executionTime,
            'memory_used' => $memoryUsed,
            'peak_memory' => $peakMemoryUsed,
            'query_count' => $queryCount,
            'labs_processed' => $labs->count(),
            'lab_labels' => $labLabels->count(),
            'panels' => $panels->count(),
            'grouped_specimens' => $groupedLabs->count(),
        ];

        echo "\n=== FULL LAB.TEST.TXT BENCHMARK RUN $i ===\n";
        echo 'Execution Time: '.round($executionTime, 2)." ms\n";
        echo 'Memory Used: '.round($memoryUsed / 1024 / 1024, 2)." MB\n";
        echo 'Peak Memory: '.round($peakMemoryUsed / 1024 / 1024, 2)." MB\n";
        echo 'Database Queries: '.$queryCount."\n";
        echo 'Labs Processed: '.$labs->count()."\n";
        echo 'Lab Labels: '.$labLabels->count()."\n";
        echo 'Panels: '.$panels->count()."\n";
        echo 'Grouped Specimens: '.$groupedLabs->count()."\n";
        echo "=======================================\n";
    }

    // Calculate averages
    $avgTime = array_sum(array_column($results, 'execution_time')) / 3;
    $avgMemory = array_sum(array_column($results, 'memory_used')) / 3;
    $avgPeakMemory = array_sum(array_column($results, 'peak_memory')) / 3;
    $avgQueries = array_sum(array_column($results, 'query_count')) / 3;

    echo "\n";
    echo "=========================================\n";
    echo "  FULL LAB.TEST.TXT PERFORMANCE SUMMARY \n";
    echo "=========================================\n";
    echo 'Average Execution Time: '.round($avgTime, 2)." ms\n";
    echo 'Average Memory Usage: '.round($avgMemory / 1024 / 1024, 2)." MB\n";
    echo 'Average Peak Memory: '.round($avgPeakMemory / 1024 / 1024, 2)." MB\n";
    echo 'Average Query Count: '.round($avgQueries, 1)."\n";
    echo 'Labs Processed: '.$results[1]['labs_processed']."\n";
    echo 'Lab Labels: '.$results[1]['lab_labels']."\n";
    echo 'Panels: '.$results[1]['panels']."\n";
    echo 'Grouped Specimens: '.$results[1]['grouped_specimens']."\n";
    echo "=========================================\n";

    // Assertions for data integrity
    expect($labs)->toBeInstanceOf(Collection::class);
    expect($labLabels)->toBeInstanceOf(Collection::class);
    expect($datetimeHeaders)->toBeInstanceOf(Collection::class);
    expect($panels)->toBeInstanceOf(Collection::class);

    // Performance expectations (adjusted for full dataset)
    expect($avgTime)->toBeLessThan(30000, 'Average execution should be within 30 seconds');
    expect($avgMemory)->toBeLessThan(100 * 1024 * 1024, 'Average memory should be less than 100MB');
    expect($avgQueries)->toBeLessThan(100, 'Average queries should not exceed 100');
    expect($results[1]['labs_processed'])->toBeGreaterThan(0, 'Should process labs successfully');
});

it('benchmarks MicroBuilder performance for comparison', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));

    $startTime = microtime(true);
    $startMemory = memory_get_usage(true);

    DB::enableQueryLog();

    $microBuilder = new MicroBuilder($rawLabs);
    $microBuilder->build();

    $micro = $microBuilder->getMicroCollection();
    $microLabels = $microBuilder->getMicroLabels();
    $microDateTimeHeaders = $microBuilder->getDateTimeHeaders();

    $queries = DB::getQueryLog();
    $queryCount = count($queries);

    $endTime = microtime(true);
    $endMemory = memory_get_usage(true);

    $executionTime = ($endTime - $startTime) * 1000;
    $memoryUsed = $endMemory - $startMemory;

    echo "\n=== MICRO BENCHMARK ===\n";
    echo 'Execution Time: '.round($executionTime, 2)." ms\n";
    echo 'Memory Used: '.round($memoryUsed / 1024 / 1024, 2)." MB\n";
    echo 'Database Queries: '.$queryCount."\n";
    echo 'Micros Processed: '.$micro->count()."\n";
    echo 'Micro Labels: '.$microLabels->count()."\n";
    echo "=======================\n";

    expect($micro)->toBeInstanceOf(Collection::class);
    expect($microLabels)->toBeInstanceOf(Collection::class);
});

it('benchmarks view rendering simulation', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));

    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    $labs = $labBuilder->getLabCollection();
    $labLabels = $labBuilder->getLabLabels();

    $startTime = microtime(true);

    // Simulate the nested loops in the Blade template
    $cellCount = 0;
    $operationCount = 0;

    foreach ($labLabels as $labLabel) {
        $grouped = $labs->groupBy('specimen_unique_id');
        $operationCount++; // groupBy per label

        foreach ($grouped as $specimen) {
            $lab = $specimen->where('name', $labLabel['name'])->first();
            $operationCount++; // where per specimen
            $cellCount++;
        }
    }

    $endTime = microtime(true);
    $viewTime = ($endTime - $startTime) * 1000;

    echo "\n=== VIEW RENDERING BENCHMARK ===\n";
    echo 'View Rendering Time: '.round($viewTime, 2)." ms\n";
    echo 'Total Operations: '.$operationCount."\n";
    echo 'Table Cells Rendered: '.$cellCount."\n";
    echo 'Operations per Cell: '.round($operationCount / max($cellCount, 1), 2)."\n";
    echo "===============================\n";

    expect($viewTime)->toBeLessThan(10000, 'View rendering should be under 10 seconds');
    expect($operationCount)->toBeGreaterThan(0, 'Should perform operations');
});
