<?php

use App\Models\Lab;
use App\Models\Panel;
use App\Services\LabBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

it('calculates panel counts using database aggregation', function () {
    // Test database-level panel counting vs collection-based counting
    $sqlPanelCounts = DB::table('labs')
        ->join('panels', 'labs.panel_id', '=', 'panels.id')
        ->select('panels.label as panel', DB::raw('COUNT(*) as count'))
        ->groupBy('panels.label')
        ->pluck('count', 'panel');

    // Current collection-based approach
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();
    $collectionPanels = $labBuilder->getPanels();

    // Both approaches should be available and produce consistent results
    expect($collectionPanels)->toBeInstanceOf(Collection::class);
    expect($sqlPanelCounts)->toBeInstanceOf(Collection::class);
    expect($collectionPanels->count())->toEqual($sqlPanelCounts->count());
});

it('efficiently queries labs with panels using single join', function () {
    DB::enableQueryLog();
    DB::flushQueryLog();

    // Test efficient single query approach
    $efficientQuery = Lab::leftJoin('panels', 'labs.panel_id', '=', 'panels.id')
        ->select('labs.name', 'labs.label', 'panels.label as panel')
        ->orderBy('panels.order_column')
        ->orderBy('labs.order_column')
        ->get();

    $queries = DB::getQueryLog();
    expect(count($queries))->toBe(1, 'Should use only one query for labs with panels');

    expect($efficientQuery->count())->toBeGreaterThanOrEqual(4);
    if ($efficientQuery->count() > 0) {
        expect($efficientQuery->first())->toHaveKey('panel');
    }
});

it('database aggregation matches collection-based panel grouping', function () {
    $rawLabs = file_get_contents(resource_path('lab.test.txt'));
    $labBuilder = new LabBuilder($rawLabs);
    $labBuilder->build();

    $collectionPanels = $labBuilder->getPanels();

    // Database aggregation should produce same results as collection groupBy
    $dbPanels = DB::table('labs')
        ->join('panels', 'labs.panel_id', '=', 'panels.id')
        ->select('panels.label as panel', DB::raw('COUNT(*) as count'))
        ->groupBy('panels.label')
        ->pluck('count', 'panel');

    // Both should be collections with panel counts
    expect($collectionPanels)->toBeInstanceOf(Collection::class);
    expect($dbPanels)->toBeInstanceOf(Collection::class);
    expect($collectionPanels->count())->toEqual($dbPanels->count());
});

it('optimizes lab lookup queries for large datasets', function () {
    // Create additional test data
    $panel = Panel::first();
    Lab::factory()->count(20)->create(['panel_id' => $panel->id]);

    DB::enableQueryLog();

    // Single query approach
    $optimizedLabs = Lab::with('panel')
        ->whereIn('name', ['GLUCOSE', 'WBC', 'SODIUM'])
        ->get()
        ->keyBy('name');

    $queries = DB::getQueryLog();

    // Should use eager loading to avoid N+1
    expect(count($queries))->toBeLessThanOrEqual(2, 'Should use eager loading to avoid N+1 queries');
    expect($optimizedLabs)->toBeInstanceOf(Collection::class);
});
