<?php

namespace App\Services\Types;

use App\Enums\RowType;

class Whitespace extends Type
{
    public RowType $type = RowType::Whitespace;

    public function getType(string $row, int $index, array $output): RowType
    {
        if ('' === $row) {
            return RowType::Whitespace;
        }

        return RowType::Other;
    }
}
