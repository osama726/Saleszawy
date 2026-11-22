<?php

declare(strict_types=1);

namespace App\Filament\Blocks;

use App\Enums\BlockType;

/**
 * Maps a BlockType to the class that describes its form fields.
 */
final class BlockRegistry
{
    /** @var array<string, class-string<Block>> */
    private const DEFINITIONS = [
        'hero' => HeroBlock::class,
        'partners' => PartnersBlock::class,
        'problem_solution' => ProblemSolutionBlock::class,
        'showcase' => ShowcaseBlock::class,
        'features_explorer' => FeaturesExplorerBlock::class,
        'pbx' => PbxBlock::class,
        'steps' => StepsBlock::class,
        'stats' => StatsBlock::class,
        'comparison' => ComparisonBlock::class,
        'pricing' => PricingBlock::class,
        'integrations' => IntegrationsBlock::class,
        'faq' => FaqBlock::class,
        'blog' => BlogBlock::class,
        'about' => AboutBlock::class,
        'contact' => ContactBlock::class,
        'cta' => CtaBlock::class,
    ];

    /**
     * @return array<int, mixed>
     */
    public static function fields(BlockType|string|null $type): array
    {
        $key = $type instanceof BlockType ? $type->value : (string) $type;
        $definition = self::DEFINITIONS[$key] ?? null;

        return $definition !== null ? $definition::fields() : [];
    }
}
