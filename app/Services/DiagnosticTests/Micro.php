<?php

namespace App\Services\DiagnosticTests;

use DateTime;

class Micro implements DiagnosticTestInterface
{
    public string $source;

    public string $specimen;

    public DateTime $collectDate;

    public DateTime $resultDate;

    public string $result;

    public array $sensitivities;

    //Todo: handle multiple organisms in 1 result
    // maybe have $result1, $sensitivities1, $result2..etc. I have never seen more than 3
    // or use a micro result class that has 2 props: string $result and array $sensitivities
    // and have a $results array that can store as many classes as needed.  yea!!

    public function result(): array
    {
        // TODO: Implement display() method.
    }
}
