<?php

namespace App\Services;

class Labs
{
    public array $micro = [];

    public array $panels = [];

    public function listMicro(): void
    {
        dd('Micro parts: '.implode(', ', $this->micro)."\n\n");
    }
}
