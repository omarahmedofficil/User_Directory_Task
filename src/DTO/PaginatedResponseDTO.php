<?php

/* Paginated Response DTO - Structures paginated API responses */

declare(strict_types=1);

namespace App\DTO;

final class PaginatedResponseDTO implements \JsonSerializable
{
    /**
     * @param UserListItemDTO[] $data
     */
    public function __construct(
        public readonly int $page,
        public readonly int $pageSize,
        public readonly int $totalCount,
        public readonly int $totalPages,
        public readonly array $data,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'page' => $this->page,
            'pageSize' => $this->pageSize,
            'totalCount' => $this->totalCount,
            'totalPages' => $this->totalPages,
            'data' => $this->data,
        ];
    }
}
