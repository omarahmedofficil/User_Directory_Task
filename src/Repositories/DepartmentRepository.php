<?php

/* Retrieves department data from the database */

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Entities\Department;
use PDO;

final class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM departments ORDER BY id ASC');

        return array_map(
            static fn (array $row) => Department::fromRow($row),
            $stmt->fetchAll(),
        );
    }
}
