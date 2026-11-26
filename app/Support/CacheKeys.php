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

    public static function faqs(?string $group = null): string
    {
        return $group === null || $group === '' ? 'faqs:all' : 'faqs:group:' . $group;
    }

    public static function partners(): string
    {
        return 'partners:list';
    }

    public static function docTree(): string
    {
        return 'docs:tree';
    }

    public static function docArticle(string $sectionSlug, string $articleSlug): string
    {
        return 'docs:article:' . $sectionSlug . ':' . $articleSlug;
    }
}
