<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusCatalogPlugin\Behat\Page\Admin\Catalog;

use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;
use Tests\BitBag\SyliusCatalogPlugin\Behat\Behaviour\ContainsEmptyListTrait;

class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    use ContainsEmptyListTrait;

    public function deleteCatalog(string $name): void
    {
        $this->deleteResourceOnPage(['name' => $name]);
    }
}
