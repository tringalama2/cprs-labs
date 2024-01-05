<?php

namespace App\Services\Parser;

use Carbon\Carbon;
use Illuminate\Support\Str;

class MicroParser
{
    const ACCESSION_UNIQUE_ID_PATTERN = '/Accession \[UID\]: ([A-Z-a-z-0-9 ]+)(?<! )/';

    const COLLECTION_DATE_PATTERN = '/(?<=Collection date: )([A-Za-z]{3} [\d]{2}, [\d]{4}( [\d]{2}:[\d]{2})?)/';

    const COMPLETED_DATE_PATTERN = '/(?<=completed: )([A-Za-z]{3} [\d]{2}, [\d]{4}( [\d]{2}:[\d]{2})?)/';

    const SAMPLE_PATTERN = '/(?<=Collection sample: )([A-Za-z 0-9]+)?(?=Collection date:)/';

    const PROVIDER_PATTERN = '/(?<=Provider: )([A-Za-z 0-9,]+)/';

    const SPECIMEN_PATTERN = '/(?<=Site\/Specimen: )([A-Za-z 0-9,]+)/';

    const TEST_PATTERN = '/(?<= Test\(s\) ordered: )([A-Za-z 0-9,\-&\/#\(\)]+)(?=[\.]*)/';

    const RESULT_PATTERN = '/Test\(s\) ordered: ([\w\W]*?)=--=--=/';

    const DATETIME_CARBON_FORMAT = 'M d, Y H:i';

    public static function getAccessionUniqueId(string $microRows): string
    {
        return Str::of($microRows)->match(self::ACCESSION_UNIQUE_ID_PATTERN);
    }

    public static function getCollectionDate(string $microRows): bool|Carbon
    {
        return self::getDate($microRows, self::COLLECTION_DATE_PATTERN, self::DATETIME_CARBON_FORMAT);
    }

    public static function getDate(string $haystack, string $pattern, string $carbonFormat): bool|Carbon
    {
        $datetime = Str::of($haystack)->match($pattern);

        if (strlen($datetime) < 12) {
            return false;
        }

        if (strlen($datetime) === 12) {
            return Carbon::createFromFormat($carbonFormat, $datetime.'@00:00');
        }

        return Carbon::createFromFormat($carbonFormat, $datetime);
    }

    public static function getCompletedDate(string $microRows): bool|Carbon
    {
        return self::getDate($microRows, self::COMPLETED_DATE_PATTERN, self::DATETIME_CARBON_FORMAT);
    }

    public static function getSample(string $microRows): string
    {
        return Str::of($microRows)->match(self::SAMPLE_PATTERN)->trim();
    }

    public static function getProvider(string $microRows): string
    {
        return Str::of($microRows)->match(self::PROVIDER_PATTERN);
    }

    public static function getSpecimen(string $microRows): string
    {

        return Str::of($microRows)->match(self::SPECIMEN_PATTERN);
    }

    public static function getTestName(string $microRows): string
    {
        return Str::of($microRows)->match(self::TEST_PATTERN)->trim();
    }

    public static function getResult(string $microRows): string
    {
        return Str::of($microRows)->match(self::RESULT_PATTERN)->trim();
    }
}
