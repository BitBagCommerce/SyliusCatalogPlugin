<?php
/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private RepositoryInterface $catalogRepository;

    private ProductsInsideCatalogResolverInterface $productResolver;

    public function __construct(
        RepositoryInterface $catalogRepository,
        ProductsInsideCatalogResolverInterface $productResolver
    ) {
        $this->catalogRepository = $catalogRepository;
        $this->productResolver = $productResolver;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_a($resourceClass, CatalogInterface::class, true);
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        /** @var CatalogInterface|null $catalog */
        $catalog = $this->catalogRepository->findOneBy(['id' => $id]);

        if (null === $catalog) {
            return null;
        }

        $products = $this->productResolver->findMatchingProducts($catalog);
        $catalog->setProducts($products);

        return $catalog;
    }
}
