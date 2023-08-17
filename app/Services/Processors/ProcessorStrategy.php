<?php

namespace App\Services\Processors;

interface ProcessorStrategy
{
    public function processData(array $data);
}
