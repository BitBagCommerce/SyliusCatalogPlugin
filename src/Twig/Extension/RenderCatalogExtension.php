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

use BitBag\SyliusCatalogPlugin\Entity\Catalog;
use BitBag\SyliusCatalogPlugin\Resolver\CatalogResourceResolverInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductResolverInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;

final class RenderCatalogExtension extends AbstractExtension
{
    /** @var EngineInterface */
    private $engine;

    /** @var CatalogResourceResolverInterface */
    private $catalogResolver;

    /** @var ProductResolverInterface */
    private $productResolver;

    public function __construct(EngineInterface $engine, CatalogResourceResolverInterface $catalogResolver,
                                ProductResolverInterface $productResolver)
    {
        $this->productResolver = $productResolver;
        $this->engine = $engine;
        $this->catalogResolver = $catalogResolver;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('bitbag_render_product_catalog', [$this, 'renderProductCatalog'], ['is_safe' => ['html']]),
        ];
    }

    public function renderProductCatalog(?string $code, ?string $template = null): string
    {
        /** @var Catalog $catalog */
        $catalog = $this->catalogResolver->findOrLog($code);
        $products = [];

        if ($catalog) {
            $products = $this->productResolver->findMatchingProducts($catalog);
        }

        if (empty($products) !== null && $catalog !== null) {
            $template = $template ?? '@BitBagSyliusCatalogPlugin/Catalog/showProducts.html.twig';

            return $this->engine->render($template, ['products' => $products, 'catalog' => $catalog]);
        }

        return ' ';
    }
}
