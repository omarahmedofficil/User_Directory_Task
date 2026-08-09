<?php

/* Maps user domain objects to API response DTOs */

declare(strict_types=1);

namespace App\Mappers;

use App\Domain\Entities\User;
use App\DTO\UserDetailDTO;
use App\DTO\UserListItemDTO;

final class UserMapper
{
    public static function toListItem(User $user): UserListItemDTO
    {
        return new UserListItemDTO(
            id: $user->id,
            fullName: $user->fullName(),
            email: $user->email,
            avatarUrl: $user->avatarUrl,
            jobTitle: $user->jobTitle,
            departmentName: $user->department?->name ?? '',
        );
    }

    public static function toDetail(User $user): UserDetailDTO
    {
        $department = $user->department;

        return new UserDetailDTO(
            id: $user->id,
            firstName: $user->firstName,
            lastName: $user->lastName,
            fullName: $user->fullName(),
            email: $user->email,
            avatarUrl: $user->avatarUrl,
            jobTitle: $user->jobTitle,
            createdAt: $user->createdAt,
            department: $department ? [
                'id' => $department->id,
                'name' => $department->name,
                'code' => $department->code,
                'createdAt' => $department->createdAt,
            ] : [],
        );
    }
}
