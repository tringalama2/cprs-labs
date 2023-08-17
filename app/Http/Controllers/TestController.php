<?php

namespace App\Http\Controllers;

use App\Enums\RowType;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public array $output = [];

    private $processor;

    public function __invoke()
    {
        //  https://regex101.com/r/dF3aE6/1

        $input = file_get_contents(resource_path('lab.test.txt'));
        $rowsRaw = preg_split("/\r\n|\n|\r/", $input);

        foreach ($rowsRaw as $index => $row) {
            if (Str::of($row)->isMatch('/^(\s)+(\[([0-9]+?)\]$)/')) {
                $this->output[$index - 1]['value'] .= $row;

                continue;
            }
            ProcessRow($this->output);
            $this->output[$index]['type'] = $this->getRowType($row, $index);
            $this->output[$index]['value'] = $row;
        }

        dd(collect($this->output)
            ->where('type', RowType::Result)
            ->pluck('value'));
    }

    private function getRowType(string $row, int $index): RowType
    {
        if ('' === $row) {
            return RowType::Whitespace;
        }

        if ('Reporting Lab' === Str::substr($row, 0, 13)) {
            return RowType::Title;
        }

        if ('               ' === Str::substr($row, 0, 15) &&
            $this->output[$index - 1]['type'] === RowType::Title) {
            return RowType::Title;
        }

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

        if ('        ' === Str::substr($row, 0, 8)
            && (($this->output[$index - 1]['type'] === RowType::Comments)
                || ($this->output[$index - 2]['type'] === RowType::Comments) && $this->output[$index - 1]['type'] === RowType::Whitespace)) {
            return RowType::Comments;
        }

        if (count(Str::of($row)->split('/(\s){2,}/')) > 1) {
            return RowType::Result;
        }

        return RowType::Other;

    }
}
