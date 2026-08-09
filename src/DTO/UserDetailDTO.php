<?php

/* Data Transfer Object - Full Profile */

declare(strict_types=1);

namespace App\DTO;

final class UserDetailDTO implements \JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $fullName,
        public readonly string $email,
        public readonly ?string $avatarUrl,
        public readonly string $jobTitle,
        public readonly string $createdAt,
        public readonly array $department,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'avatarUrl' => $this->avatarUrl,
            'jobTitle' => $this->jobTitle,
            'createdAt' => $this->createdAt,
            'department' => $this->department,
        ];
    }
}
