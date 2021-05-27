<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver\Elasticsearch;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\QueryBuilder\ProductQueryBuilderInterface;
use BitBag\SyliusCatalogPlugin\Repository\CatalogRepositoryInterface;
use BitBag\SyliusCatalogPlugin\Resolver\CatalogsForProductResolverInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogsForProductResolver implements CatalogsForProductResolverInterface
{
    private RepositoryInterface $catalogRepository;

    private ProductQueryBuilderInterface $productQueryBuilder;

    private PaginatedFinderInterface $paginatedFinder;

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
     * @return CatalogInterface[]
     */
    public function resolveProductCatalogs(ProductInterface $product, \DateTimeImmutable $on): array
    {
        $activeCatalogs = $this->catalogRepository->findActive($on);
        $result = [];

        /** @var CatalogInterface $activeCatalog */
        foreach ($activeCatalogs as $activeCatalog) {
            $boolQuery = new BoolQuery();
            if ($activeCatalog->getProductAssociationRules()->count()) {
                $boolQuery->addMust(
                    $this->productQueryBuilder->findMatchingProductsQuery(
                        $activeCatalog->getProductAssociationConnectingRules(),
                        $activeCatalog->getProductAssociationRules()
                    )
                );
            }

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
