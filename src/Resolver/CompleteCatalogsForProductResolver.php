<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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


    /**
     * @return array|CatalogInterface[]
     */
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
