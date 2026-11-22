<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BlockType: string implements HasLabel
{
    case Hero = 'hero';
    case Partners = 'partners';
    case ProblemSolution = 'problem_solution';
    case Showcase = 'showcase';
    case FeaturesExplorer = 'features_explorer';
    case Pbx = 'pbx';
    case Steps = 'steps';
    case Stats = 'stats';
    case Comparison = 'comparison';
    case Pricing = 'pricing';
    case Integrations = 'integrations';
    case Faq = 'faq';
    case Blog = 'blog';
    case About = 'about';
    case Contact = 'contact';
    case Cta = 'cta';

    public function getLabel(): string
    {
        return match ($this) {
            self::Hero => 'الواجهة الرئيسية (Hero)',
            self::Partners => 'شركاء النجاح',
            self::ProblemSolution => 'لماذا سيلزواي (قبل / بعد)',
            self::Showcase => 'عرض القدرات (CRM)',
            self::FeaturesExplorer => 'مستكشف القدرات',
            self::Pbx => 'ربط السنترال (IP-PBX)',
            self::Steps => 'خطوات البدء',
            self::Stats => 'أرقام وإحصائيات',
            self::Comparison => 'جدول المقارنة',
            self::Pricing => 'الباقات والأسعار',
            self::Integrations => 'التكاملات',
            self::Faq => 'الأسئلة الشائعة',
            self::Blog => 'أحدث المقالات',
            self::About => 'عن سيلزواي',
            self::Contact => 'التواصل',
            self::Cta => 'دعوة لاتخاذ إجراء (CTA)',
        };
    }
}
