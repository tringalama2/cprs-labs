<?php

namespace App\Services\DiagnosticTests\ResultFormats;

interface ResultFormatContract
{
    public function match(): bool;

    public function getResultPieces(): array;
}
