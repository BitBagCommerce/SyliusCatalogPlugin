<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver\Elasticsearch;

use BitBag\SyliusCatalogPlugin\Checker\Sort\Doctrine\SortInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\QueryBuilder\ProductQueryBuilderInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use Elastica\Query\BoolQuery;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Sylius\Component\Registry\ServiceRegistry;

final class ProductsInsideCatalogResolver implements ProductsInsideCatalogResolverInterface
{
    private ProductQueryBuilderInterface $productQueryBuilder;

    private PaginatedFinderInterface $productFinder;

    private ServiceRegistry $sortServiceRegistry;

    public function __construct(
        ProductQueryBuilderInterface $productQueryBuilder,
        PaginatedFinderInterface $paginatedFinder,
        ServiceRegistry $sortServiceRegistry
    ) {
        $this->productQueryBuilder = $productQueryBuilder;
        $this->productFinder = $paginatedFinder;
        $this->sortServiceRegistry = $sortServiceRegistry;
    }

    public function findMatchingProducts(CatalogInterface $catalog): array
    {
        $query = new BoolQuery();

        if ($catalog->getRules()->count()) {
            $query = $this->productQueryBuilder->findMatchingProductsQuery($catalog->getConnectingRules(), $catalog->getRules());
        }

        /** @var SortInterface $sortChecker */
        $sortChecker = $this->sortServiceRegistry->get($catalog->getSortingType());
        $query = $sortChecker->modifyQueryBuilder($query);

        $products = $this->productFinder->find($query, $catalog->getDisplayProducts());

        return $products;
    }
}
