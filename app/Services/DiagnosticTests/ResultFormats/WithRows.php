<?php

namespace App\Services\DiagnosticTests\ResultFormats;

use Illuminate\Support\Str;

trait WithRows
{
    public function splitRowBy2SpaceDelimiters($resultRow): array
    {
        return Str::of($resultRow)->split('/(\s){2,}/')->flatten()->toArray();
    }

    public function stripFlagFromResult(string $resultRow): string
    {
        return Str::of($resultRow)->match('/([H|L]\**)$/');
    }
}
