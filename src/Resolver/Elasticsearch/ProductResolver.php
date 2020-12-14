<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver\Elasticsearch;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\QueryBuilder\ProductQueryBuilderInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductResolverInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class ProductResolver implements ProductResolverInterface
{
    /** @var ProductQueryBuilderInterface */
    private $productQueryBuilder;

    /** @var PaginatedFinderInterface */
    private $productFinder;

    public function __construct(ProductQueryBuilderInterface $productQueryBuilder, PaginatedFinderInterface $paginatedFinder)
    {
        $this->productQueryBuilder = $productQueryBuilder;
        $this->productFinder = $paginatedFinder;
    }

    public function findMatchingProducts(CatalogInterface $catalog)
    {
        $query = $this->productQueryBuilder->findMatchingProductsQuery($catalog);
        $products = $this->productFinder->findPaginated($query);

        return $products->getCurrentPageResults();
    }
}
