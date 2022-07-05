<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

    public function renderProductCatalogs(
        ProductInterface $product,
        ?string $date = null,
        ?string $template = null
    ): string {
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
