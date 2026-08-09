<?php

/* User List Item DTO - Defines the data structure for a user in API responses */

declare(strict_types=1);

namespace App\DTO;

final class UserListItemDTO implements \JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $fullName,
        public readonly string $email,
        public readonly ?string $avatarUrl,
        public readonly string $jobTitle,
        public readonly string $departmentName,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'avatarUrl' => $this->avatarUrl,
            'jobTitle' => $this->jobTitle,
            'departmentName' => $this->departmentName,
        ];
    }
}
