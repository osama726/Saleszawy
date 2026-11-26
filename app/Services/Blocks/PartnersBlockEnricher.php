<?php

declare(strict_types=1);

namespace App\Services\Blocks;

use App\Services\PartnerPayloadService;

final class PartnersBlockEnricher implements BlockEnricher
{
    public function __construct(private readonly PartnerPayloadService $partners) {}

    public function enrich(array $block): array
    {
        $block['data']['items'] = $this->partners->all();

        return $block;
    }
}
