<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Menu;
use App\Settings\FooterSettings;
use App\Settings\GeneralSettings;
use Illuminate\Support\Collection;

final readonly class LayoutData
{
    /**
     * @param  Collection<string, Menu>  $menus  visible menus keyed by their `key`
     */
    public function __construct(
        public GeneralSettings $general,
        public FooterSettings $footer,
        public Collection $menus,
    ) {}

    public function menu(string $key): ?Menu
    {
        return $this->menus->get($key);
    }
}
