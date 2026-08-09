<?php

/* Parses and provides access to request method, path, and query parameters */

declare(strict_types=1);

namespace App\Http;

final class Request
{
    public readonly string $method;
    public readonly string $path;
    public readonly string $basePath;
    public readonly string $appPath;
    /** @var array<string,string> */
    public readonly array $query;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->path = rtrim((string) parse_url($uri, PHP_URL_PATH), '/') ?: '/';
        parse_str((string) parse_url($uri, PHP_URL_QUERY), $query);
        $this->query = $query;

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $this->basePath = $basePath === '/' ? '' : $basePath;

        $appPath = $this->path;
        if ($this->basePath !== '' && str_starts_with($appPath, $this->basePath)) {
            $appPath = substr($appPath, strlen($this->basePath));
        }
        $this->appPath = $appPath === '' ? '/' : $appPath;
    }

    public function queryParam(string $key, ?string $default = null): ?string
    {
        return isset($this->query[$key]) ? (string) $this->query[$key] : $default;
    }
}
