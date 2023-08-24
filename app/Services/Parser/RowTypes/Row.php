<?php

namespace App\Services\Parser\RowTypes;

use Illuminate\Support\Str;

class Row
{
    public static function isCollectedDate($row): bool
    {
        return Str::startsWith($row, '    Specimen Collection Date');
    }

    public static function isResult($row): bool
    {
        return ! str_starts_with($row, ' ') && count(Str::of($row)->split('/(\s){2,}/')) > 2;
    }

    public static function hasSiteCode($row): bool
    {
        return Str::of($row)->isMatch('/\[[\d]+\]$/');
    }

    public static function isMicroHeader($row): bool
    {
        return Str::contains($row, '---- MICROBIOLOGY ----');
    }

    public static function isSeparator($row): bool
    {
        return '===============================================================================' === $row;
    }

    public static function isOverflow($row): bool
    {
        // older overflow pattern, missed some rows...
        //const OVERFLOW_ROW_PATTERN = '/^(\s)+([A-Za-z: ]*)(\[([0-9]+?)\]$)/';

        return Str::of($row)->isMatch('/^(\s)+(.)*(\[([0-9]+?)\]$)/');
    }

    //    protected function getRowType(string $row): RowType
    //    {
    //        if ('' === $row) {
    //            return RowType::Whitespace;
    //        }
    //
    //        if (Str::contains($row, '---- MICROBIOLOGY ----')) {
    //            return RowType::MicroHeader;
    //        }
    //
    //        if ('Reporting Lab' === Str::substr($row, 0, 13)) {
    //            return RowType::Title;
    //        }
    //
    //        if ('Report Released Date/Time' === Str::substr($row, 0, 25)) {
    //            return RowType::ReleaseDate;
    //        }
    //
    //        if ('    Specimen Collection Date' === Str::substr($row, 0, 28)) {
    //            return RowType::CollectionDate;
    //        }
    //
    //        if ('  Specimen:' === Str::substr($row, 0, 11)) {
    //            return RowType::Specimen;
    //        }
    //
    //        if ('Provider:' === Str::substr($row, 0, 9)) {
    //            return RowType::OrderingProvider;
    //        }
    //
    //        if ('      Test name' === Str::substr($row, 0, 15)) {
    //            return RowType::Heading;
    //        }
    //
    //        if ('====' === Str::substr($row, 0, 4)) {
    //            return RowType::Separator;
    //        }
    //
    //        if ('      Eval:' === Str::substr($row, 0, 11)) {
    //            return RowType::Notes;
    //        }
    //
    //        if ('Comment:' === Str::substr($row, 0, 8)) {
    //            return RowType::Comments;
    //        }
    //
    //        if ('===============================================================================' === $row) {
    //            return RowType::SeparatorLine;
    //        }
    //
    //        if (count(Str::of($row)->split('/(\s){2,}/')) > 2) {
    //            return RowType::Result;
    //        }
    //
    //        return RowType::Other;
    //
    //    }
}
