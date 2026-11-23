<?php

declare(strict_types=1);

namespace App\Actions\Layout;

use App\DTOs\LayoutData;
use App\Models\Menu;
use App\Settings\FooterSettings;
use App\Settings\GeneralSettings;

final class GetLayoutAction
{
    public function execute(): LayoutData
    {
        $menus = Menu::query()
            ->visible()
            ->with('rootItems.children')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->keyBy('key');

        return new LayoutData(
            app(GeneralSettings::class),
            app(FooterSettings::class),
            $menus,
        );
    }
}
