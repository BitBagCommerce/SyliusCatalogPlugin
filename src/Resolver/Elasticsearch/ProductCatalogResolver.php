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

use BitBag\SyliusCatalogPlugin\Entity\Catalog;
use BitBag\SyliusCatalogPlugin\QueryBuilder\ProductQueryBuilderInterface;
use BitBag\SyliusCatalogPlugin\Repository\CatalogRepositoryInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductCatalogResolverInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductCatalogResolver implements ProductCatalogResolverInterface
{
    /** @var RepositoryInterface */
    private $catalogRepository;

    /** @var ServiceRegistry */
    private $serviceRegistry;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ProductQueryBuilderInterface */
    private $productQueryBuilder;

    /** @var PaginatedFinderInterface */
    private $paginatedFinder;

    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        ProductQueryBuilderInterface $productQueryBuilder,
        PaginatedFinderInterface $paginatedFinder
    ) {
        $this->catalogRepository = $catalogRepository;
        $this->productQueryBuilder = $productQueryBuilder;
        $this->paginatedFinder = $paginatedFinder;
    }

    /**
     * @return Catalog[]
     */
    public function resolveProductCatalogs(ProductInterface $product, \DateTimeImmutable $on): array
    {
        $activeCatalogs = $this->catalogRepository->findActive($on);
        $result = [];

        /** @var Catalog $activeCatalog */
        foreach ($activeCatalogs as $activeCatalog) {
            $boolQuery = new BoolQuery();
            $boolQuery->addMust($this->productQueryBuilder->findMatchingProductsQuery($activeCatalog));
            $idTerm = new Term();
            $idTerm->setTerm('_id', $product->getId());
            $boolQuery->addMust($idTerm);

            $matching = $this->paginatedFinder->findPaginated($boolQuery);

            if (0 < $matching->getNbResults()) {
                $result[] = $activeCatalog;
            }
        }

        return $result;
    }
}
