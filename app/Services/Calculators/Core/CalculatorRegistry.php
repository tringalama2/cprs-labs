<?php

namespace App\Services\Calculators\Core;

use App\Services\Calculators\Contracts\CalculatorInterface;
use Illuminate\Support\Collection;

class CalculatorRegistry
{
    private array $calculators = [];

    public function __construct()
    {
        $this->registerCalculators();
    }

    /**
     * Register all available calculators
     */
    private function registerCalculators(): void
    {
        $calculatorPath = app_path('Services/Calculators/Calculators');

        if (! is_dir($calculatorPath)) {
            return;
        }

        $files = glob($calculatorPath.'/*.php');

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $className = "App\\Services\\Calculators\\Calculators\\{$filename}";

            // Check if the class exists and implements the CalculatorInterface
            if (class_exists($className)) {
                $reflection = new \ReflectionClass($className);

                // Skip abstract classes and ensure it implements CalculatorInterface
                if (! $reflection->isAbstract() && $reflection->implementsInterface(CalculatorInterface::class)) {
                    $calculator = new $className();
                    $this->register($calculator);
                }
            }
        }
    }

    /**
     * Register a calculator
     */
    public function register(CalculatorInterface $calculator): void
    {
        $this->calculators[$calculator->getName()] = $calculator;
    }

    /**
     * Get all registered calculators
     */
    public function getAllCalculators(): Collection
    {
        return collect($this->calculators);
    }

    /**
     * Get a specific calculator by name
     */
    public function getCalculator(string $name): ?CalculatorInterface
    {
        return $this->calculators[$name] ?? null;
    }

    /**
     * Calculate all applicable calculators for the given labs
     */
    public function calculateAll(Collection $labs): Collection
    {
        $resolver = new LabValueResolver($labs);
        $results = collect();

        $applicableCalculators = $this->getApplicableCalculators($labs);

        foreach ($applicableCalculators as $calculator) {
            $result = $calculator->calculate($resolver);
            if ($result !== null) {
                $results->push($result);
            }
        }

        return $results;
    }

    /**
     * Get calculators that can be applied to the given lab collection
     */
    public function getApplicableCalculators(Collection $labs): Collection
    {
        return collect($this->calculators)
            ->filter(fn (CalculatorInterface $calculator) => $calculator->isApplicable($labs))
            ->sortBy(fn (CalculatorInterface $calculator) => $calculator->getPriority());
    }

    /**
     * Get calculator names for debugging
     */
    public function getCalculatorNames(): array
    {
        return array_keys($this->calculators);
    }
}
