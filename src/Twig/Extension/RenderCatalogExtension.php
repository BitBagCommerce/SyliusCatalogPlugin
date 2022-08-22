<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
        /** @var CatalogInterface|null $catalog */
        $catalog = $this->catalogResolver->findOrLog($code);
        $products = [];

        if (null !== $catalog) {
            $products = $this->productResolver->findMatchingProducts($catalog);
        }

        if (0 !== count($products) && null !== $catalog) {
            $template = $catalog->getTemplate() ?? '@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig';

            return $this->engine->render($template, ['products' => $products, 'catalog' => $catalog]);
        }

        return ' ';
    }
}
