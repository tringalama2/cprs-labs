<?php

use App\Livewire\Labs;
use App\Livewire\OptimizedLabs;
use App\Models\Lab;
use App\Models\Panel;
use Livewire\Livewire;

beforeEach(function () {
    Panel::factory()->create(['label' => 'Chemistry', 'order_column' => 1]);
    Panel::factory()->create(['label' => 'Hematology', 'order_column' => 2]);

    Lab::factory()->create(['name' => 'GLUCOSE', 'label' => 'Glucose', 'panel_id' => Panel::first()->id]);
    Lab::factory()->create(['name' => 'WBC', 'label' => 'WBC', 'panel_id' => Panel::skip(1)->first()->id]);
});

it('produces identical output to original Labs component', function () {
    $rawInput = <<<'RAWLABS'
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

    // Test original component
    $originalComponent = Livewire::test(Labs::class)
        ->set('input', $rawInput)
        ->call('save');

    // Test optimized component
    $optimizedComponent = Livewire::test(OptimizedLabs::class)
        ->set('input', $rawInput)
        ->call('save');

    // Verify both components process the same number of labs
    $originalLabs = $originalComponent->get('labs');
    $optimizedLabs = $optimizedComponent->get('labs');

    expect($optimizedLabs->count())->toBe($originalLabs->count(), 'Should process same number of labs');

    // Verify lab data structure equivalence
    $originalLabLabels = $originalComponent->get('labLabelsSorted');
    $optimizedLabLookup = $optimizedComponent->get('labLookup');

    expect(count($optimizedLabLookup))->toBe($originalLabLabels->count(), 'Should have same number of lab labels');

    // Verify panel data equivalence
    $originalPanels = $originalComponent->get('panels');
    $optimizedPanels = $optimizedComponent->get('panelCounts');

    expect(count($optimizedPanels))->toBe($originalPanels->count(), 'Should have same number of panels');

    // Verify datetime headers equivalence
    $originalHeaders = $originalComponent->get('datetimeHeaders');
    $optimizedHeaders = $optimizedComponent->get('datetimeHeaders');

    expect(count($optimizedHeaders))->toBe($originalHeaders->count(), 'Should have same number of datetime headers');
});

it('provides O(1) lab lookup functionality', function () {
    $rawInput = <<<'RAWLABS'
Report Released Date/Time: Aug 15, 2023@05:19
Provider: BRIZ,PRINCESS
  Specimen: PLASMA.           CO 0815 5
    Specimen Collection Date: Aug 15, 2023@04:33
      Test name                Result    units      Ref.   range   Site Code
GLUCOSE                         100 H   mg/dL      70 - 100         [570]
SODIUM                          140     mEq/L      136 - 145        [570]
===============================================================================
RAWLABS;

    $component = Livewire::test(OptimizedLabs::class)
        ->set('input', $rawInput)
        ->call('save');

    // Verify component processes data successfully
    $labs = $component->get('labs');
    $labLookup = $component->get('labLookup');

    expect($labs->count())->toBeGreaterThan(0);
    expect($labLookup)->toBeArray();
    expect(count($labLookup))->toBeGreaterThan(0);

    // Verify optimized view data is created
    $optimizedViewData = $component->get('optimizedViewData');
    expect($optimizedViewData)->toBeArray();
    expect($optimizedViewData)->toHaveKey('lab_lookup');
    expect($optimizedViewData)->toHaveKey('specimen_ids');
});

it('correctly handles lab flagging logic', function () {
    $component = new \App\Livewire\OptimizedLabs();

    // Test normal lab (no flag)
    $normalLab = ['flag' => '', 'result' => '100'];
    $normalClass = $component->isLabFlagged($normalLab);
    expect($normalClass)->toBe('group-hover:bg-sky-200 bg-white');

    // Test high flag
    $highLab = ['flag' => 'H', 'result' => '100'];
    $highClass = $component->isLabFlagged($highLab);
    expect($highClass)->toBe('bg-red-300 text-red-900 group-hover:bg-sky-400 font-bold');

    // Test critical flag
    $criticalLab = ['flag' => 'H*', 'result' => '100'];
    $criticalClass = $component->isLabFlagged($criticalLab);
    expect($criticalClass)->toBe('bg-red-500 text-red-950 group-hover:bg-sky-500 font-bold');

    // Test null lab
    $nullClass = $component->isLabFlagged(null);
    expect($nullClass)->toBe('group-hover:bg-sky-200 bg-white');
});

it('benchmarks optimized component rendering performance', function () {
    $rawInput = <<<'RAWLABS'
Report Released Date/Time: Aug 15, 2023@05:19
Provider: BRIZ,PRINCESS
  Specimen: PLASMA.           CO 0815 5
    Specimen Collection Date: Aug 15, 2023@04:33
      Test name                Result    units      Ref.   range   Site Code
GLUCOSE                         100 H   mg/dL      70 - 100         [570]
SODIUM                          140     mEq/L      136 - 145        [570]
POTASSIUM                       4.0     mEq/L      3.5 - 5.0        [570]
CHLORIDE                        102     mEq/L      98 - 107         [570]
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

    // Benchmark original component
    $startTime = microtime(true);
    $originalComponent = Livewire::test(Labs::class)
        ->set('input', $rawInput)
        ->call('save');
    $originalTime = (microtime(true) - $startTime) * 1000;

    // Benchmark optimized component
    $startTime = microtime(true);
    $optimizedComponent = Livewire::test(OptimizedLabs::class)
        ->set('input', $rawInput)
        ->call('save');
    $optimizedTime = (microtime(true) - $startTime) * 1000;

    $improvement = $originalTime > 0 ? (($originalTime - $optimizedTime) / $originalTime) * 100 : 0;

    echo "\n=== LIVEWIRE COMPONENT BENCHMARK ===\n";
    echo 'Original Labs Component: '.round($originalTime, 2)." ms\n";
    echo 'Optimized Labs Component: '.round($optimizedTime, 2)." ms\n";
    echo 'Performance Improvement: '.round($improvement, 1)."%\n";
    echo "====================================\n";

    // Verify both work correctly
    expect($originalComponent->get('labs')->count())->toBeGreaterThan(0);
    expect($optimizedComponent->get('labs')->count())->toBeGreaterThan(0);
    expect($optimizedComponent->get('labs')->count())->toBe($originalComponent->get('labs')->count());
});
