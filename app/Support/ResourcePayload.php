<?php

declare(strict_types=1);

namespace App\Support;

use JsonSerializable;

final class ResourcePayload
{
    /**
     * Turns an API Resource (or resource collection) into a plain array,
     * so it can be cached without serializing Eloquent models.
     *
     * @return array<mixed>
     */
    public static function toArray(JsonSerializable $resource): array
    {
        return json_decode((string) json_encode($resource), true, 512, JSON_THROW_ON_ERROR);
    }
}
