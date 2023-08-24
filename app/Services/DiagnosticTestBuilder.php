<?php

namespace App\Services;

use Illuminate\Support\Str;

abstract class DiagnosticTestBuilder
{
    const OVERFLOW_ROW_PATTERN = '/^(\s)+([A-Za-z: ]*)(\[([0-9]+?)\]$)/';

    protected array $labRows;

    protected string $rawLabs;

    private Labs $labs;

    public function __construct($rawLabs)
    {
        $this->reset();
        $this->rawLabs = $rawLabs;
        $this->labRows = $this->rawLabToRows($this->rawLabs);
        $this->fixOverflowRows();
        // repeat for some rows that overflow twice
        $this->fixOverflowRows();
        $this->labRows = array_values($this->labRows);
    }

    private function reset(): void
    {
        $this->labs = new Labs;
    }

    private function rawLabToRows($rawLabString): array
    {
        return preg_split("/\r\n|\n|\r/", $rawLabString);
    }

    private function fixOverflowRows(): void
    {
        $overflowRows = array_filter($this->labRows, [$this, 'is_overflow']);
        foreach ($overflowRows as $index => $row) {
            // append overflow row to prior row
            $this->labRows[$index - 1] .= $row;
            // remove overflow
            unset($this->labRows[$index]);
        }

    }

    abstract public function process(): void;

    public function getLabs(): Labs
    {
        $lab = $this->labs;
        $this->reset();

        return $lab;
    }

    private function is_overflow($row): bool
    {
        return Str::of($row)->isMatch(self::OVERFLOW_ROW_PATTERN);
    }
}
