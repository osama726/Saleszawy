<?php

declare(strict_types=1);

namespace App\Services\Blocks;

use App\Services\PostPayloadService;

final class BlogBlockEnricher implements BlockEnricher
{
    public function __construct(private readonly PostPayloadService $posts) {}

    public function enrich(array $block): array
    {
        $limit = $block['data']['limit'] ?? 3;
        $limit = is_numeric($limit) && (int) $limit > 0 ? (int) $limit : 3;

        $block['data']['items'] = $this->posts->all(limit: $limit)['posts'];

        return $block;
    }
}
