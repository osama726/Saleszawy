<?php

declare(strict_types=1);

namespace App\Support;

final class CacheKeys
{
    public static function page(string $slug): string
    {
        return 'pages:' . $slug;
    }
}
