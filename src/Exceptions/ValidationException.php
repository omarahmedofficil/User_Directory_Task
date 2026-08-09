<?php

/* Handles invalid request data with HTTP 400 status */

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class ValidationException extends Exception
{
    public function __construct(string $message = 'Invalid request')
    {
        parent::__construct($message, 400);
    }
}
