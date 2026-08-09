<?php

/* Defines the contract for user data access */

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    /**
     * @return array{items: User[], totalCount: int}
     */
    public function paginate(int $page, int $pageSize, ?string $searchTerm): array;

    public function findById(int $id): ?User;
}
