<?php
/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */
declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Choices;

interface CatalogInterface
{
    public const DEFAULT_TEMPLATE = ['default' => '@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig'];

    public function getTemplates(): array;
}
