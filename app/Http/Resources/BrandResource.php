<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property GeneralSettings $resource
 */
final class BrandResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $settings = $this->resource;

        return [
            'name' => $settings->site_name,
            'text' => $settings->logo_text,
            'badge' => $settings->logo_badge,
            'logo' => $settings->logo_path ? Storage::disk('public')->url($settings->logo_path) : null,
            'url' => '/',
        ];
    }
}
