<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusCatalogPlugin\Behat\Page\Admin\Catalog;

use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePageInterface;
use Tests\BitBag\SyliusCatalogPlugin\Behat\Behaviour\ChecksCodeImmutabilityInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface, ChecksCodeImmutabilityInterface
{
}
