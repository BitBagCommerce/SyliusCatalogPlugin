<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
