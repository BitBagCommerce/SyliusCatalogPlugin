<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    public function addAdminMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubMenu = $menu
            ->addChild('bitbag_sylius_catalog_plugin.ui.catalogs');

        $newSubMenu
            ->addChild('bitbag_sylius_catalog_plugin.ui.catalog', [
                'route' => 'bitbag_sylius_catalog_plugin_admin_catalog_index',
            ])
            ->setLabelAttribute('icon', 'folder');
    }
}
