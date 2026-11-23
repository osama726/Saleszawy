<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\LayoutData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property LayoutData $resource
 */
final class HeaderResource extends JsonResource
{
    public const LINKS_MENU = 'header-links';

    public const ACTIONS_MENU = 'header-actions';

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $layout = $this->resource;

        return [
            'brand' => BrandResource::make($layout->general),
            'links' => MenuItemResource::collection($layout->menu(self::LINKS_MENU)?->rootItems ?? collect()),
            'actions' => MenuItemResource::collection($layout->menu(self::ACTIONS_MENU)?->rootItems ?? collect()),
        ];
    }
}
