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
use BitBag\SyliusCatalogPlugin\Resolver\ProductCatalogResolverInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductResolverInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;

final class RenderProductCatalogsExtension extends AbstractExtension
{
    /** @var EngineInterface */
    private $engine;

    /** @var ProductCatalogResolverInterface */
    private $productCatalogResolver;

    /** @var ProductResolverInterface */
    private $productResolver;

    public function __construct(
        EngineInterface $engine,
        ProductCatalogResolverInterface $productCatalogResolver,
        ProductResolverInterface $productResolver
        ) {
        $this->engine = $engine;
        $this->productCatalogResolver = $productCatalogResolver;
        $this->productResolver = $productResolver;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('bitbag_render_product_catalogs', [$this, 'renderProductCatalogs'], ['is_safe' => ['html']]),
        ];
    }

    public function renderProductCatalogs(ProductInterface $product, ?string $date = null, ?string $template = null): string
    {
        $catalogs = $this->productCatalogResolver->resolveProductCatalogs($product, new \DateTimeImmutable($date ?? 'now'));

        $template = $template ?? '@BitBagSyliusCatalogPlugin/Product/showCatalogs.html.twig';

        $catalogs = array_map(
            function (Catalog $catalog) {
                return [
                    'catalog' => $catalog,
                    'products' => $this->productResolver->findMatchingProducts($catalog),
                ];
            },
            $catalogs
        );

        return $this->engine->render($template, ['catalogs' => $catalogs]);
    }
}
