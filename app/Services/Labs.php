<?php

namespace App\Services;

use JetBrains\PhpStorm\NoReturn;

class Labs
{
    public array $micro = [];
    public array $panels = [];

    public function listMicro(): void
    {
        dd( "Micro parts: " . implode(', ', $this->micro) . "\n\n");
    }
}
