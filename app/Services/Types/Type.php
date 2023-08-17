<?php

namespace App\Services\Types;

use App\Enums\RowType;

abstract class Type
{
    abstract public function getType(string $row, int $index, array $output): RowType;
}
