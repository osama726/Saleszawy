<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PageBlock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PageBlock
 */
final class PageBlockResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'type' => $this->type->value,
            'data' => $this->data ?: (object) [],
        ];
    }
}
