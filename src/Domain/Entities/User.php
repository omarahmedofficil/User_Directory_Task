<?php

/* User domain entity (the "Many" side of the One-to-Many relationship). */

declare(strict_types=1);

namespace App\Domain\Entities;

final class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $avatarUrl,
        public readonly string $jobTitle,
        public readonly int $departmentId,
        public readonly string $createdAt,
        public readonly ?Department $department = null,
    ) {
    }

    public function fullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public static function fromRow(array $row): self
    {
        $department = null;
        if (isset($row['department_id_join'])) {
            $department = new Department(
                id: (int) $row['department_id_join'],
                name: (string) $row['department_name'],
                code: (string) $row['department_code'],
                createdAt: (string) $row['department_created_at'],
            );
        }

        return new self(
            id: (int) $row['id'],
            firstName: (string) $row['first_name'],
            lastName: (string) $row['last_name'],
            email: (string) $row['email'],
            avatarUrl: $row['avatar_url'] ?? null,
            jobTitle: (string) $row['job_title'],
            departmentId: (int) $row['department_id'],
            createdAt: (string) $row['created_at'],
            department: $department,
        );
    }
}
