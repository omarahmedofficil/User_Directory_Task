<?php

/* Registers routes and dispatches requests to their handlers */

declare(strict_types=1);

namespace App\Http;

final class Router
{
    /** @var array<int, array{method:string, pattern:string, handler:callable}> */
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $this->routes[] = ['method' => 'GET', 'pattern' => $pattern, 'handler' => $handler];
    }

    public function dispatch(Request $request): bool
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method) {
                continue;
            }

            $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $route['pattern']);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $request->appPath, $matches)) {
                array_shift($matches);
                ($route['handler'])(...$matches);

                return true;
            }
        }

        return false;
    }
}
