<?php

/* Bootstraps the application, handles API routes, and serves frontend assets */

declare(strict_types=1);

require dirname(__DIR__) . '/src/Autoload.php';

use App\Config\Database;
use App\Controllers\UserController;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;
use App\Middleware\ExceptionHandlerMiddleware;
use App\Repositories\UserRepository;
use App\Services\UserService;

$request = new Request();

if (str_starts_with($request->appPath, '/api/')) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    ExceptionHandlerMiddleware::handle(function () use ($request): void {
        $pdo = Database::connection();
        $userRepository = new UserRepository($pdo);
        $userService = new UserService($userRepository);
        $controller = new UserController($userService);

        $router = new Router();
        $router->get('/api/users', fn () => $controller->index($request));
        $router->get('/api/users/{id}', fn (string $id) => $controller->show($id));

        $matched = $router->dispatch($request);

        if (!$matched) {
            Response::json(['status' => 404, 'error' => 'Route not found'], 404);
        }
    });

    return;
}

$assetPath = __DIR__ . $request->appPath;
if ($request->appPath !== '/' && is_file($assetPath)) {
    return false;
}

$html = file_get_contents(__DIR__ . '/index.html');
$baseTag = '<base href="' . htmlspecialchars($request->basePath, ENT_QUOTES) . '/">';
$bootScript = '<script>window.__APP_BASE__ = ' . json_encode($request->basePath, JSON_UNESCAPED_SLASHES) . ';</script>';
$html = str_replace('<head>', "<head>\n{$baseTag}\n{$bootScript}", $html);

echo $html;
