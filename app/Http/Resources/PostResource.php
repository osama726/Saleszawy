<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
final class PostResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            PostSummaryResource::make($this->resource)->toArray($request),
            [
                'content' => $this->content,
                'seo' => [
                    'title' => $this->seo_title,
                    'description' => $this->seo_description,
                ],
            ],
        );
    }
}
