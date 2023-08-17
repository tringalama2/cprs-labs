<?php

namespace App\Services\Types;

use App\Enums\RowType;

class Other extends Type
{
    public function getType(string $row, int $index, array $output): RowType
    {
        return RowType::Other;
    }
}
