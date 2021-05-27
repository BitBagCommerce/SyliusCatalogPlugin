<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Twig\Extension;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Resolver\CatalogResourceResolverInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderCatalogExtension extends AbstractExtension
{
    private Environment $engine;

    private CatalogResourceResolverInterface $catalogResolver;

    private ProductsInsideCatalogResolverInterface $productResolver;

    public function __construct(
        Environment $engine,
        CatalogResourceResolverInterface $catalogResolver,
        ProductsInsideCatalogResolverInterface $productResolver
    ) {
        $this->productResolver = $productResolver;
        $this->engine = $engine;
        $this->catalogResolver = $catalogResolver;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_render_product_catalog', [$this, 'renderProductCatalog'], ['is_safe' => ['html']]),
        ];
    }

    public function renderProductCatalog(?string $code): string
    {
        /** @var CatalogInterface $catalog */
        $catalog = $this->catalogResolver->findOrLog($code);
        $products = [];

        if (null !== $catalog) {
            $products = $this->productResolver->findMatchingProducts($catalog);
        }

        if (empty($products) !== null && $catalog !== null) {
            $template = $catalog->getTemplate() ?? '@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig';

            return $this->engine->render($template, ['products' => $products, 'catalog' => $catalog]);
        }

        return ' ';
    }
}
