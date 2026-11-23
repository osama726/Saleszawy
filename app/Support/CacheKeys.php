<?php

declare(strict_types=1);

namespace App\Support;

final class CacheKeys
{
    public static function page(string $slug): string
    {
        return 'pages:' . $slug;
    }

    public static function plans(): string
    {
        return 'plans:list';
    }

    public static function layout(): string
    {
        return 'layout:payload';
    }
}
