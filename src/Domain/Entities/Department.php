<?php

/* Department domain entity (the "One" side of the One-to-Many relationship). */

declare(strict_types=1);

namespace App\Domain\Entities;

final class Department
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $code,
        public readonly string $createdAt,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            name: (string) $row['name'],
            code: (string) $row['code'],
            createdAt: (string) $row['created_at'],
        );
    }
}
