<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\DocArticle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin DocArticle
 */
final class DocSearchResultResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'snippet' => $this->snippet(),
            'section' => $this->whenLoaded('section', fn (): array => [
                'title' => $this->section->title,
                'slug' => $this->section->slug,
            ]),
        ];
    }

    private function snippet(): string
    {
        if (filled($this->excerpt)) {
            return (string) $this->excerpt;
        }

        $plain = preg_replace('/\s+/u', ' ', strip_tags((string) $this->content)) ?? '';

        return Str::limit(trim($plain), 160);
    }
}
