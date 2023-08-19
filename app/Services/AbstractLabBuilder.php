<?php

namespace App\Services;

use App\Enums\RowType;
use Illuminate\Support\Str;

abstract class AbstractLabBuilder
{
    const OVERFLOW_ROW_PATTERN = '/^(\s)+([A-Za-z: ]*)(\[([0-9]+?)\]$)/';

    const SPECIMEN_PATTERN = '/Specimen: ([A-Z-a-z ]+)/';

    const DATE_PATTERN = '/([A-Za-z]{3} [\d]{2}, [\d]{4}(@[\d]{2}:[\d]{2})?)/';

    const DATETIME_CARBON_FORMAT = 'M d, Y@H:i';

    const DATE_CARBON_FORMAT = 'M d, Y';

    public array $labRows;

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

    protected function getRowType(string $row, int $index): RowType
    {
        if ('' === $row) {
            return RowType::Whitespace;
        }

        if (Str::contains($row, '---- MICROBIOLOGY ----')) {
            return RowType::MicroHeader;
        }

        if ('Reporting Lab' === Str::substr($row, 0, 13)) {
            return RowType::Title;
        }

        //        if ('               ' === Str::substr($row, 0, 15) &&
        //            $this->output[$index - 1]['type'] === RowType::Title) {
        //            return RowType::Title;
        //        }

        if ('Report Released Date/Time' === Str::substr($row, 0, 25)) {
            return RowType::ReleaseDate;
        }

        if ('    Specimen Collection Date' === Str::substr($row, 0, 28)) {
            return RowType::CollectionDate;
        }

        if ('  Specimen:' === Str::substr($row, 0, 11)) {
            return RowType::Specimen;
        }

        if ('Provider:' === Str::substr($row, 0, 9)) {
            return RowType::OrderingProvider;
        }

        if ('      Test name' === Str::substr($row, 0, 15)) {
            return RowType::Heading;
        }

        if ('====' === Str::substr($row, 0, 4)) {
            return RowType::Separator;
        }

        if ('      Eval:' === Str::substr($row, 0, 11)) {
            return RowType::Notes;
        }

        if ('Comment:' === Str::substr($row, 0, 8)) {
            return RowType::Comments;
        }

        if ('===============================================================================' === $row) {
            return RowType::SeparatorLine;
        }

        //        if ('        ' === Str::substr($row, 0, 8)
        //            && (($this->output[$index - 1]['type'] === RowType::Comments)
        //                || ($this->output[$index - 2]['type'] === RowType::Comments) && $this->output[$index - 1]['type'] === RowType::Whitespace)) {
        //            return RowType::Comments;
        //        }

        if (count(Str::of($row)->split('/(\s){2,}/')) > 2) {
            return RowType::Result;
        }

        return RowType::Other;

    }

    private function is_overflow($row): bool
    {
        return Str::of($row)->isMatch(self::OVERFLOW_ROW_PATTERN);
    }
}
