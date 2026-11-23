<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\DTOs\LayoutData;
use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property LayoutData $resource
 */
final class FooterResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $layout = $this->resource;
        $footer = $layout->footer;

        return [
            'brand' => BrandResource::make($layout->general),
            'description' => $footer->description,
            'columns' => $layout->menus
                ->filter(static fn (Menu $menu): bool => $menu->location === MenuLocation::Footer)
                ->values()
                ->map(static fn (Menu $menu): array => [
                    'key' => $menu->key,
                    'title' => $menu->title,
                    'items' => MenuItemResource::collection($menu->rootItems),
                ])
                ->all(),
            'socials' => $footer->socials,
            'copyright' => str_replace('{year}', (string) now()->year, $footer->copyright),
            'back_to_top_label' => $footer->back_to_top_label,
            'floating_cta' => $footer->floating_cta_enabled
                ? ['label' => $footer->floating_cta_label, 'url' => $footer->floating_cta_url]
                : null,
        ];
    }
}
