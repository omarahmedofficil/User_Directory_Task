<?php

/* Retrieves and queries user data from the database */

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Entities\User;
use PDO;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    private const BASE_SELECT = <<<SQL
        SELECT
            u.id,
            u.first_name,
            u.last_name,
            u.email,
            u.avatar_url,
            u.job_title,
            u.department_id,
            u.created_at,
            d.id   AS department_id_join,
            d.name AS department_name,
            d.code AS department_code,
            d.created_at AS department_created_at
        FROM users u
        INNER JOIN departments d ON d.id = u.department_id
    SQL;

    public function paginate(int $page, int $pageSize, ?string $searchTerm): array
    {
        $where = '';
        $params = [];

        if ($searchTerm !== null && $searchTerm !== '') {
            $where = " WHERE u.first_name LIKE :term OR u.last_name LIKE :term
                OR u.email LIKE :term OR u.job_title LIKE :term OR d.name LIKE :term";
            $params[':term'] = '%' . $searchTerm . '%';
        }

        $countSql = 'SELECT COUNT(*) AS total FROM users u INNER JOIN departments d ON d.id = u.department_id' . $where;
        $countStmt = $this->pdo->prepare($countSql);
        $countStmt->execute($params);
        $totalCount = (int) $countStmt->fetch()['total'];

        $offset = ($page - 1) * $pageSize;
        $sql = self::BASE_SELECT . $where . ' ORDER BY u.id ASC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $pageSize, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $items = array_map(
            static fn (array $row) => User::fromRow($row),
            $stmt->fetchAll(),
        );

        return ['items' => $items, 'totalCount' => $totalCount];
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare(self::BASE_SELECT . ' WHERE u.id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? User::fromRow($row) : null;
    }
}
