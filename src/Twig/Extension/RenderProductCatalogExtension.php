<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Twig\Extension;

use Twig\Extension\AbstractExtension;

final class RenderProductCatalogExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('bitbag_render_product_catalog', [$this, 'renderProductCatalog'])
        ];
    }

    public function renderProductCatalog(): string
    {
        return "Hello!";
    }
}
