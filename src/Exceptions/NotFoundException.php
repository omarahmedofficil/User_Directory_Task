<?php

/* Handles missing resources with HTTP 404 status */

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class NotFoundException extends Exception
{
    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message, 404);
    }
}
