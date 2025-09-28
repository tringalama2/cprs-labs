<?php

use App\Services\LabBuilder;
use App\Services\OptimizedLabBuilder;
use Illuminate\Support\Facades\DB;

it('benchmarks optimized vs original LabBuilder performance', function () {
    $rawLabs = <<<'RAWLABS'
Printed at:
FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]
2615 EAST CLINTON AVE FRESNO, CA 93703-2223
As of: Aug 15, 2023@09:20

Reporting Lab: FRESNO VA MEDICAL CENTER [CLIA# 05D0988207]
               2615 EAST CLINTON AVE FRESNO, CA 93703-2223

Report Released Date/Time: Aug 15, 2023@05:19
Provider: BRIZ,PRINCESS
  Specimen: PLASMA.           CO 0815 5
    Specimen Collection Date: Aug 15, 2023@04:33
      Test name                Result    units      Ref.   range   Site Code
GLUCOSE                         100 H   mg/dL      70 - 100         [570]
SODIUM                          140     mEq/L      136 - 145        [570]
POTASSIUM                       4.0     mEq/L      3.5 - 5.0        [570]
CHLORIDE                        102     mEq/L      98 - 107         [570]
BUN                             20      mg/dL      7 - 18           [570]
CREATININE                      1.0     mg/dL      0.6 - 1.2        [570]
===============================================================================

Report Released Date/Time: Aug 15, 2023@05:53
Provider: IZUKA,SHANNON HANA
  Specimen: BLOOD.            HE 0815 38
    Specimen Collection Date: Aug 15, 2023@04:33
      Test name                Result    units      Ref.   range   Site Code
WBC                             8.1     10*3/uL    4.0 - 11.0       [570]
RBC                             4.5     10*6/uL    4.7 - 6.1        [570]
HGB,Blood                      14.0     g/dL       14.0 - 17.0      [570]
HCT,Blood                      42.0     %          42.0 - 52.0      [570]
PLT                            250      K/cmm      150 - 400        [570]
===============================================================================

Report Released Date/Time: Aug 15, 2023@06:15
Provider: SMITH,JOHN DOE
  Specimen: SERUM.            CH 0815 15
    Specimen Collection Date: Aug 15, 2023@05:00
      Test name                Result    units      Ref.   range   Site Code
ALBUMIN                         4.0     g/dL       3.5 - 5.0        [570]
TOTAL PROTEIN                   7.2     g/dL       6.0 - 8.3        [570]
===============================================================================
RAWLABS;

    // Benchmark Original LabBuilder (3 runs average)
    $originalResults = [];
    for ($i = 1; $i <= 3; $i++) {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        DB::enableQueryLog();
        DB::flushQueryLog();

        $originalBuilder = new LabBuilder($rawLabs);
        $originalBuilder->build();
        $originalBuilder->sort(true);

        $originalLabs = $originalBuilder->getLabCollection();
        $originalLabels = $originalBuilder->getLabLabels();
        $originalPanels = $originalBuilder->getPanels();
        $originalHeaders = $originalBuilder->getDateTimeHeaders();

        // Simulate view operations (the expensive O(n*m) operations)
        $originalGrouped = $originalLabs->groupBy('specimen_unique_id');

        $originalQueries = DB::getQueryLog();

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $originalResults[$i] = [
            'execution_time' => ($endTime - $startTime) * 1000,
            'memory_used' => $endMemory - $startMemory,
            'query_count' => count($originalQueries),
            'labs_processed' => $originalLabs->count(),
        ];
    }

    // Benchmark Optimized LabBuilder (3 runs average)
    $optimizedResults = [];
    for ($i = 1; $i <= 3; $i++) {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        DB::enableQueryLog();
        DB::flushQueryLog();

        $optimizedBuilder = new OptimizedLabBuilder($rawLabs);
        $optimizedBuilder->build();
        $optimizedBuilder->sort(true);

        $optimizedLabs = $optimizedBuilder->getLabCollection();
        $optimizedLabels = $optimizedBuilder->getLabLabels(); // Returns array instead of collection
        $optimizedPanels = $optimizedBuilder->getPanels(); // Returns array instead of collection
        $optimizedHeaders = $optimizedBuilder->getDateTimeHeaders(); // Returns array instead of collection

        // Get optimized view data (pre-computed for O(1) access)
        $optimizedViewData = $optimizedBuilder->getOptimizedViewData();

        $optimizedQueries = DB::getQueryLog();

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $optimizedResults[$i] = [
            'execution_time' => ($endTime - $startTime) * 1000,
            'memory_used' => $endMemory - $startMemory,
            'query_count' => count($optimizedQueries),
            'labs_processed' => $optimizedLabs->count(),
        ];
    }

    // Calculate averages
    $originalAvgTime = array_sum(array_column($originalResults, 'execution_time')) / 3;
    $originalAvgMemory = array_sum(array_column($originalResults, 'memory_used')) / 3;
    $originalAvgQueries = array_sum(array_column($originalResults, 'query_count')) / 3;

    $optimizedAvgTime = array_sum(array_column($optimizedResults, 'execution_time')) / 3;
    $optimizedAvgMemory = array_sum(array_column($optimizedResults, 'memory_used')) / 3;
    $optimizedAvgQueries = array_sum(array_column($optimizedResults, 'query_count')) / 3;

    // Calculate improvements
    $timeImprovement = $originalAvgTime > 0 ? (($originalAvgTime - $optimizedAvgTime) / $originalAvgTime) * 100 : 0;
    $memoryImprovement = $originalAvgMemory > 0 ? (($originalAvgMemory - $optimizedAvgMemory) / $originalAvgMemory) * 100 : 0;
    $queryImprovement = $originalAvgQueries > 0 ? (($originalAvgQueries - $optimizedAvgQueries) / $originalAvgQueries) * 100 : 0;

    echo "\n";
    echo "=============================================\n";
    echo "   OPTIMIZED VS ORIGINAL PERFORMANCE        \n";
    echo "=============================================\n";
    echo "Original LabBuilder (Collection-based):\n";
    echo '  - Avg Execution Time: '.round($originalAvgTime, 2)." ms\n";
    echo '  - Avg Memory Usage: '.round($originalAvgMemory / 1024, 2)." KB\n";
    echo '  - Avg Query Count: '.round($originalAvgQueries, 1)."\n";
    echo '  - Labs Processed: '.$originalResults[1]['labs_processed']."\n";
    echo "---------------------------------------------\n";
    echo "Optimized LabBuilder (Array + Generator):\n";
    echo '  - Avg Execution Time: '.round($optimizedAvgTime, 2)." ms\n";
    echo '  - Avg Memory Usage: '.round($optimizedAvgMemory / 1024, 2)." KB\n";
    echo '  - Avg Query Count: '.round($optimizedAvgQueries, 1)."\n";
    echo '  - Labs Processed: '.$optimizedResults[1]['labs_processed']."\n";
    echo "---------------------------------------------\n";
    echo "Performance Improvements:\n";
    echo '  - Time Improvement: '.round($timeImprovement, 1)."%\n";
    echo '  - Memory Improvement: '.round($memoryImprovement, 1)."%\n";
    echo '  - Query Improvement: '.round($queryImprovement, 1)."%\n";
    echo "=============================================\n";

    // Data integrity verification
    expect($optimizedLabs->count())->toBe($originalLabs->count(), 'Should process same number of labs');
    expect(count($optimizedLabels))->toBe($originalLabels->count(), 'Should have same number of lab labels');

    // Verify optimized view data structure
    expect($optimizedViewData)->toHaveKeys([
        'lab_lookup',
        'labs_by_specimen',
        'datetime_headers',
        'panel_counts',
        'specimen_ids',
    ]);

    // Performance expectations
    expect($optimizedAvgTime)->toBeLessThan($originalAvgTime * 1.2, 'Optimized version should not be significantly slower');
    expect($optimizedResults[1]['labs_processed'])->toBe(13, 'Should process 13 lab results');

    // Verify arrays are used for lookups
    expect($optimizedLabels)->toBeArray('Lab labels should be array for fast lookups');
    expect($optimizedPanels)->toBeArray('Panels should be array for memory efficiency');
    expect($optimizedHeaders)->toBeArray('Headers should be array for memory efficiency');
});

it('benchmarks view rendering with optimized data structures', function () {
    $rawLabs = <<<'RAWLABS'
Report Released Date/Time: Aug 15, 2023@05:19
Provider: BRIZ,PRINCESS
  Specimen: PLASMA.           CO 0815 5
    Specimen Collection Date: Aug 15, 2023@04:33
      Test name                Result    units      Ref.   range   Site Code
GLUCOSE                         100 H   mg/dL      70 - 100         [570]
SODIUM                          140     mEq/L      136 - 145        [570]
POTASSIUM                       4.0     mEq/L      3.5 - 5.0        [570]
===============================================================================

Report Released Date/Time: Aug 15, 2023@05:53
Provider: IZUKA,SHANNON HANA
  Specimen: BLOOD.            HE 0815 38
    Specimen Collection Date: Aug 15, 2023@04:33
      Test name                Result    units      Ref.   range   Site Code
WBC                             8.1     10*3/uL    4.0 - 11.0       [570]
RBC                             4.5     10*6/uL    4.7 - 6.1        [570]
===============================================================================
RAWLABS;

    // Original view rendering approach
    $originalBuilder = new LabBuilder($rawLabs);
    $originalBuilder->build();
    $originalLabs = $originalBuilder->getLabCollection();
    $originalLabels = $originalBuilder->getLabLabels();

    $originalStartTime = microtime(true);
    $originalOperations = 0;

    // Simulate current Blade template operations (O(n*m) complexity)
    foreach ($originalLabels as $labLabel) {
        $grouped = $originalLabs->groupBy('specimen_unique_id'); // O(n) per label
        $originalOperations++;

        foreach ($grouped as $specimen) {
            $lab = $specimen->where('name', $labLabel['name'])->first(); // O(m) per specimen
            $originalOperations++;
        }
    }

    $originalEndTime = microtime(true);
    $originalViewTime = ($originalEndTime - $originalStartTime) * 1000;

    // Optimized view rendering approach
    $optimizedBuilder = new OptimizedLabBuilder($rawLabs);
    $optimizedBuilder->build();
    $optimizedViewData = $optimizedBuilder->getOptimizedViewData();

    $optimizedStartTime = microtime(true);
    $optimizedOperations = 0;

    // Simulate optimized Blade template operations (O(1) lookup)
    foreach ($optimizedViewData['lab_lookup'] as $labName => $labLabel) {
        foreach ($optimizedViewData['specimen_ids'] as $specimenId) {
            // Direct array access O(1) instead of collection operations
            $lab = $optimizedViewData['labs_by_specimen'][$specimenId][$labName] ?? null;
            $optimizedOperations++; // Count the lookup operation
        }
    }

    $optimizedEndTime = microtime(true);
    $optimizedViewTime = ($optimizedEndTime - $optimizedStartTime) * 1000;

    $viewTimeImprovement = $originalViewTime > 0 ? (($originalViewTime - $optimizedViewTime) / $originalViewTime) * 100 : 0;
    $operationReduction = $originalOperations > 0 ? (($originalOperations - $optimizedOperations) / $originalOperations) * 100 : 0;

    echo "\n=== VIEW RENDERING OPTIMIZATION BENCHMARK ===\n";
    echo "Original View Rendering (O(n*m) complexity):\n";
    echo '  - Time: '.round($originalViewTime, 4)." ms\n";
    echo '  - Operations: '.$originalOperations."\n";
    echo "  - Complexity: O(n*m) with nested collection operations\n";
    echo "-----------------------------------------------\n";
    echo "Optimized View Rendering (O(1) lookup):\n";
    echo '  - Time: '.round($optimizedViewTime, 4)." ms\n";
    echo '  - Operations: '.$optimizedOperations."\n";
    echo "  - Complexity: O(1) with direct array access\n";
    echo "-----------------------------------------------\n";
    echo "View Rendering Improvements:\n";
    echo '  - Time Improvement: '.round($viewTimeImprovement, 1)."%\n";
    echo '  - Operation Reduction: '.round($operationReduction, 1)."%\n";
    echo "=============================================\n";

    expect($optimizedViewTime)->toBeLessThan($originalViewTime * 2, 'Optimized view should not be significantly slower');
    expect($optimizedOperations)->toBeGreaterThan(0, 'Should perform lookup operations');
});
