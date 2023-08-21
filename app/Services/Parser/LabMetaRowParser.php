<?php

namespace App\Services\Parser;

use Carbon\Carbon;
use Illuminate\Support\Str;

class LabMetaRowParser
{
    const SPECIMEN_PATTERN = '/Specimen: ([A-Z-a-z ]+)/';

    const DATE_PATTERN = '/([A-Za-z]{3} [\d]{2}, [\d]{4}(@[\d]{2}:[\d]{2})?)/';

    const DATETIME_CARBON_FORMAT = 'M d, Y@H:i';

    public function stripDateFromRow(string $row): bool|Carbon
    {
        $date = Str::of($row)->match(self::DATE_PATTERN);

        if (strlen($date) < 12) {
            return false;
        }

        if (strlen($date) === 12) {
            return Carbon::createFromFormat(self::DATETIME_CARBON_FORMAT, $date.'@00:00');
        }

        return Carbon::createFromFormat(self::DATETIME_CARBON_FORMAT, $date);
    }

    public function stripSpecimenFromRow(string $row): string
    {
        return Str::of($row)->match(self::SPECIMEN_PATTERN);
    }

    public function stripProviderFromRow(string $row): string
    {
        return Str::of($row)->substr(10);
    }
}
