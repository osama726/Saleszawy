<?php

declare(strict_types=1);

namespace App\Services\Blocks;

use App\Services\FeaturePayloadService;

final class FeaturesExplorerBlockEnricher implements BlockEnricher
{
    public function __construct(private readonly FeaturePayloadService $features) {}

    public function enrich(array $block): array
    {
        $group = $block['data']['group'] ?? 'home';
        $group = is_string($group) && $group !== '' ? $group : 'home';

        $payload = $this->features->all($group);
        $block['data']['categories'] = $payload['categories'];
        $block['data']['items'] = $payload['features'];

        return $block;
    }
}
