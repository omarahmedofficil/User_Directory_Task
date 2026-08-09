<?php

/* User Controller */

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\UserService;

final class UserController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(Request $request): void
    {
        $page = (int) ($request->queryParam('page', '1'));
        $pageSize = (int) ($request->queryParam('pageSize', '10'));
        $searchTerm = $request->queryParam('searchTerm');

        $result = $this->userService->getPaginatedUsers($page, $pageSize, $searchTerm);

        Response::json($result, 200);
    }

    public function show(string $id): void
    {
        $result = $this->userService->getUserById((int) $id);

        Response::json($result, 200);
    }
}
