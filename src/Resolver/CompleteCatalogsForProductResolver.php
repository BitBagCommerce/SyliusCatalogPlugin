<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class CompleteCatalogsForProductResolver implements CatalogsForProductResolverInterface
{
    private CatalogsForProductResolverInterface $wrappedResolver;

    private ProductsInsideCatalogResolverInterface $catalogResolver;

    public function __construct(
        CatalogsForProductResolverInterface $wrappedResolver,
        ProductsInsideCatalogResolverInterface $catalogResolver
    ) {
        $this->wrappedResolver = $wrappedResolver;
        $this->catalogResolver = $catalogResolver;
    }

    public function resolveProductCatalogs(ProductInterface $product, \DateTimeImmutable $dataTime): array
    {
        return
            array_values(
                array_filter(
                    array_map(
                        function (CatalogInterface $catalog) {
                            return [
                                'catalog' => $catalog,
                                'products' => $this->catalogResolver->findMatchingProducts($catalog),
                            ];
                        },
                        $this->wrappedResolver->resolveProductCatalogs($product, $dataTime)
                    ),
                    function (array $catalogData) {
                        return 0 < count($catalogData['products']);
                    }
                )
            )
        ;
    }
}
