<?php

/* Defines the contract for department data access */

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Entities\Department;

interface DepartmentRepositoryInterface
{
    /** @return Department[] */
    public function all(): array;
}
