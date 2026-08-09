<?php

/* Handles user business logic, validation, and response preparation */

declare(strict_types=1);

namespace App\Services;

use App\DTO\PaginatedResponseDTO;
use App\DTO\UserDetailDTO;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Mappers\UserMapper;
use App\Repositories\UserRepositoryInterface;


final class UserService
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function getPaginatedUsers(int $page, int $pageSize, ?string $searchTerm): PaginatedResponseDTO
    {
        if ($page < 1) {
            throw new ValidationException('page must be greater than or equal to 1');
        }

        if ($pageSize < 1 || $pageSize > 100) {
            throw new ValidationException('pageSize must be between 1 and 100');
        }

        $result = $this->users->paginate($page, $pageSize, $searchTerm);
        $totalCount = $result['totalCount'];
        $totalPages = $totalCount > 0 ? (int) ceil($totalCount / $pageSize) : 0;

        $data = array_map(
            static fn ($user) => UserMapper::toListItem($user),
            $result['items'],
        );

        return new PaginatedResponseDTO(
            page: $page,
            pageSize: $pageSize,
            totalCount: $totalCount,
            totalPages: $totalPages,
            data: $data,
        );
    }

    public function getUserById(int $id): UserDetailDTO
    {
        if ($id < 1) {
            throw new ValidationException('id must be a positive integer');
        }

        $user = $this->users->findById($id);

        if ($user === null) {
            throw new NotFoundException("User with id {$id} was not found");
        }

        return UserMapper::toDetail($user);
    }
}
