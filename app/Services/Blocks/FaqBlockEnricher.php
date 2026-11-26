<?php

declare(strict_types=1);

namespace App\Services\Blocks;

use App\Services\FaqPayloadService;

final class FaqBlockEnricher implements BlockEnricher
{
    public function __construct(private readonly FaqPayloadService $faqs) {}

    public function enrich(array $block): array
    {
        $group = $block['data']['group'] ?? null;
        $group = is_string($group) && $group !== '' ? $group : null;

        // No group on the block = all published questions.
        $block['data']['items'] = $this->faqs->all($group);

        return $block;
    }
}
