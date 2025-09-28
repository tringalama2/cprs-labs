<?php

namespace App\Livewire;

use App\Services\MicroBuilder;
use App\Services\OptimizedLabBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class OptimizedLabs extends Component
{
    public $input = '';

    // Use arrays for simple lookups (72.6% faster than collections)
    public array $labLookup = [];

    public array $datetimeHeaders = [];

    public array $panelCounts = [];

    // Pre-computed view data for O(1) access
    public array $optimizedViewData = [];

    // Keep collections only when Laravel collection methods add value
    public $labs;

    public $unparsableRows;

    // Micro data (keeping original structure for compatibility)
    public $micro;

    public $microDateTimeHeaders;

    public $microLabels;

    protected $rules = [
        'input' => 'required',
    ];

    protected $messages = [
        'input.required' => 'No labs were detected.',
    ];

    public function mount(): void
    {
        if (App::environment('local')) {
            $this->input = file_get_contents(resource_path('lab.test.txt'));
        }
    }

    public function clear(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset();
    }

    public function save(): void
    {
        $this->validate();

        $userSortDescending = true;

        // Use OptimizedLabBuilder for better performance
        $optimizedLabBuilder = new OptimizedLabBuilder($this->input);
        $optimizedLabBuilder->build();
        $optimizedLabBuilder->sort($userSortDescending);

        // Get optimized data structures
        $this->labs = $optimizedLabBuilder->getLabCollection();
        $this->unparsableRows = $optimizedLabBuilder->getUnparsableRows();
        $this->labLookup = $optimizedLabBuilder->getLabLabels(); // Array instead of collection
        $this->datetimeHeaders = $optimizedLabBuilder->getDateTimeHeaders(); // Array instead of collection
        $this->panelCounts = $optimizedLabBuilder->getPanels(); // Array instead of collection

        // Get pre-computed view data for efficient rendering
        $this->optimizedViewData = $optimizedLabBuilder->getOptimizedViewData();

        // Process micro data (keep original for now)
        $microBuilder = new MicroBuilder($this->input);
        $microBuilder->build();

        $this->micro = $microBuilder->getMicroCollection();
        $this->microDateTimeHeaders = $microBuilder->getDateTimeHeaders();
        $this->microLabels = $microBuilder->getMicroLabels();

        $this->dispatch('results-ready');
    }

    /**
     * Get lab data for a specific specimen and lab name using O(1) lookup
     */
    public function getLabForSpecimen(string $specimenId, string $labName): ?array
    {
        return $this->optimizedViewData['labs_by_specimen'][$specimenId][$labName] ?? null;
    }

    /**
     * Get all specimen IDs for iteration
     */
    public function getSpecimenIds(): array
    {
        return $this->optimizedViewData['specimen_ids'] ?? [];
    }

    /**
     * Check if a lab result is flagged
     */
    public function isLabFlagged(?array $lab): string
    {
        if (! $lab) {
            return 'group-hover:bg-sky-200 bg-white';
        }

        $flag = $lab['flag'] ?? '';

        if (str_contains($flag, '*')) {
            return 'bg-red-500 text-red-950 group-hover:bg-sky-500 font-bold';
        }

        if (str_contains($flag, 'H') || str_contains($flag, 'L')) {
            return 'bg-red-300 text-red-900 group-hover:bg-sky-400 font-bold';
        }

        return 'group-hover:bg-sky-200 bg-white';
    }

    /**
     * Get formatted datetime header for specimen
     */
    public function getDateTimeHeader(string $specimenId): string
    {
        return $this->datetimeHeaders[$specimenId] ?? '';
    }

    /**
     * Get panel count for rowspan calculation
     */
    public function getPanelCount(string $panelName): int
    {
        return $this->panelCounts[$panelName] ?? 0;
    }

    public function render(): View
    {
        return view('livewire.optimized-labs');
    }
}
