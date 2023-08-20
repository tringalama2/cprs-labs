<?php

namespace App\Services\DiagnosticTests;

class UnparsableDiagnosticTest implements DiagnosticTestInterface
{
    public function __construct(public array $row)
    {
    }

    public function result(): array
    {
        return $this->row;
    }
}
