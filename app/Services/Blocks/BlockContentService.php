<?php

declare(strict_types=1);

namespace App\Services\Blocks;

use Illuminate\Contracts\Container\Container;

final class BlockContentService
{
    /** @var array<string, class-string<BlockEnricher>> */
    private const ENRICHERS = [
        'faq' => FaqBlockEnricher::class,
        'partners' => PartnersBlockEnricher::class,
        'features_explorer' => FeaturesExplorerBlockEnricher::class,
    ];

    public function __construct(private readonly Container $container) {}

    /**
     * @param  array<string, mixed>  $block
     * @return array<string, mixed>
     */
    public function enrich(array $block): array
    {
        $enricher = self::ENRICHERS[$block['type']] ?? null;

        return $enricher !== null
            ? $this->container->make($enricher)->enrich($block)
            : $block;
    }
}
