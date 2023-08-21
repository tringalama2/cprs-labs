<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class LabBuilderEmptyCollectionException extends Exception
{
    public function report(): void
    {
        Log::debug('Lab Collection is empty. Did you forget to process it first?');
    }
}
