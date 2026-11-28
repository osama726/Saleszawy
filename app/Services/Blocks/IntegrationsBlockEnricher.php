<?php

declare(strict_types=1);

namespace App\Services\Blocks;

use App\Services\IntegrationPayloadService;

final class IntegrationsBlockEnricher implements BlockEnricher
{
    public function __construct(private readonly IntegrationPayloadService $integrations) {}

    public function enrich(array $block): array
    {
        $block['data']['items'] = $this->integrations->all();

        return $block;
    }
}
