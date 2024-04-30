<?php

namespace App\Services\Calc;

interface CalcInterface
{
    public function description(): string;

    public function inputLabs(): array;

    public function result(array $inputLabs): float|int|string;
}
