<?php

/* Handles application exceptions and returns standardized API errors */

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Response;
use Throwable;

final class ExceptionHandlerMiddleware
{
    public static function handle(callable $next): void
    {
        try {
            $next();
        } catch (ValidationException $e) {
            Response::json(self::errorBody($e->getMessage(), 400), 400);
        } catch (NotFoundException $e) {
            Response::json(self::errorBody($e->getMessage(), 404), 404);
        } catch (Throwable $e) {
            error_log(sprintf(
                '[UserDirectory] %s in %s:%d\n%s',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            ));

            $config = require __DIR__ . '/../Config/config.php';
            $debug = !empty($config['debug']);

            $body = self::errorBody('An unexpected error occurred.', 500);
            if ($debug) {
                $body['debug'] = [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'type' => get_class($e),
                ];
            }

            Response::json($body, 500);
        }
    }

    private static function errorBody(string $message, int $status): array
    {
        return [
            'status' => $status,
            'error' => $message,
        ];
    }
}
