<?php

declare(strict_types=1);

namespace App\Services\Blocks;

/**
 * Adds live content (FAQ items, plans...) to a block when it is requested alone.
 * One implementation per block type; register it in BlockContentService.
 */
interface BlockEnricher
{
    /**
     * @param  array<string, mixed>  $block
     * @return array<string, mixed>
     */
    public function enrich(array $block): array;
}
