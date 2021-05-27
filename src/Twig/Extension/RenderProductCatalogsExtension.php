<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Twig\Extension;

use BitBag\SyliusCatalogPlugin\Resolver\CatalogsForProductResolverInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderProductCatalogsExtension extends AbstractExtension
{
    private Environment $engine;

    private CatalogsForProductResolverInterface $productCatalogResolver;

    public function __construct(
        Environment $engine,
        CatalogsForProductResolverInterface $productCatalogResolver
        ) {
        $this->engine = $engine;
        $this->productCatalogResolver = $productCatalogResolver;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_render_product_catalogs', [$this, 'renderProductCatalogs'], ['is_safe' => ['html']]),
        ];
    }

    public function renderProductCatalogs(ProductInterface $product, ?string $date = null, ?string $template = null): string
    {
        return $this->engine->render(
            $template ?? '@BitBagSyliusCatalogPlugin/Product/showCatalogs.html.twig',
            [
                'catalogs' => $this->productCatalogResolver->resolveProductCatalogs(
                    $product,
                    new \DateTimeImmutable($date ?? 'now')
                ),
            ]
        );
    }
}
