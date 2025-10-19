<?php

namespace App\Services\Calculators\Core;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class LabValueResolver
{
    private Collection $labs;

    public function __construct(Collection $labs)
    {
        $this->labs = $labs;
    }

    /**
     * Get a lab value collected before a specific date
     */
    public function getValue(string $labName, ?Carbon $beforeDate = null): ?float
    {
        $query = $this->labs->where('name', $labName);

        if ($beforeDate) {
            $query = $query->where('collection_date', '<', $beforeDate);
        }

        $lab = $query->sortByDesc('collection_date')->first();

        if (! $lab || ! isset($lab['result'])) {
            return null;
        }

        $result = $lab['result'];

        if (is_string($result)) {
            $cleaned = preg_replace('/[^\d.-]/', '', $result);
            if (is_numeric($cleaned)) {
                return (float) $cleaned;
            }

            return null;
        }

        return is_numeric($result) ? (float) $result : null;
    }

    /**
     * Get lab value with its units and collection date
     */
    public function getValueWithMetadata(string $labName): ?array
    {
        $lab = $this->labs
            ->where('name', $labName)
            ->sortByDesc('collection_date')
            ->first();

        if (! $lab) {
            return null;
        }

        return [
            'value' => $this->getLatestValue($labName),
            'units' => $lab['units'] ?? '',
            'collection_date' => $lab['collection_date'] ?? null,
            'reference_range' => $lab['reference_range'] ?? '',
        ];
    }

    /**
     * Get the most recent value with its collection date
     */
    public function getLatestValueWithDate(string $labName): ?array
    {
        $lab = $this->labs
            ->where('name', $labName)
            ->sortByDesc('collection_date')
            ->first();

        if (! $lab || ! isset($lab['result'])) {
            return null;
        }

        // Convert to float, handling various formats
        $result = $lab['result'];

        // Handle numeric strings
        if (is_string($result)) {
            // Remove common non-numeric characters but preserve decimals
            $cleaned = preg_replace('/[^\d.-]/', '', $result);
            if (is_numeric($cleaned)) {
                $value = (float) $cleaned;
            } else {
                return null;
            }
        } else {
            $value = is_numeric($result) ? (float) $result : null;
        }

        if ($value === null) {
            return null;
        }

        return [
            'value' => $value,
            'collection_date' => $lab['collection_date'],
        ];
    }

    /**
     * Get the most recent value for a given lab name
     */
    public function getLatestValue(string $labName): ?float
    {
        $lab = $this->labs
            ->where('name', $labName)
            ->sortByDesc('collection_date')
            ->first();

        if (! $lab || ! isset($lab['result'])) {
            return null;
        }

        // Convert to float, handling various formats
        $result = $lab['result'];

        // Handle numeric strings
        if (is_string($result)) {
            // Remove common non-numeric characters but preserve decimals
            $cleaned = preg_replace('/[^\d.-]/', '', $result);
            if (is_numeric($cleaned)) {
                return (float) $cleaned;
            }

            return null;
        }

        return is_numeric($result) ? (float) $result : null;
    }

    /**
     * Check if a lab value exists
     */
    public function hasValue(string $labName): bool
    {
        return $this->getLatestValue($labName) !== null;
    }

    /**
     * Get all values for a lab (useful for trending)
     */
    public function getAllValues(string $labName): Collection
    {
        return $this->labs
            ->where('name', $labName)
            ->sortByDesc('collection_date')
            ->map(function ($lab) {
                $value = $lab['result'];
                if (is_string($value)) {
                    $cleaned = preg_replace('/[^\d.-]/', '', $value);
                    $value = is_numeric($cleaned) ? (float) $cleaned : null;
                } else {
                    $value = is_numeric($value) ? (float) $value : null;
                }

                return [
                    'value' => $value,
                    'collection_date' => $lab['collection_date'],
                    'units' => $lab['units'] ?? '',
                ];
            })
            ->filter(fn ($item) => $item['value'] !== null);
    }

    /**
     * Debug method to see all available lab names
     */
    public function getAvailableLabNames(): array
    {
        return $this->labs->pluck('name')->unique()->sort()->values()->toArray();
    }
}
