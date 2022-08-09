<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    private CatalogRepositoryInterface $catalogRepository;

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
            if ((bool) $activeCatalog->getProductAssociationRules()->count()) {
                $boolQuery = new BoolQuery();
                if (is_null($activeCatalog->getProductAssociationConnectingRules())) {
                    continue;
                }
                $boolQuery->addMust(
                    $this->productQueryBuilder->findMatchingProductsQuery(
                        $activeCatalog->getProductAssociationConnectingRules(),
                        $activeCatalog->getProductAssociationRules()
                    )
                );


                $idTerm = new Term();
                $idTerm->setTerm('_id', $product->getId());
                $boolQuery->addMust($idTerm);

                $matching = $this->paginatedFinder->findPaginated($boolQuery);

                if (0 < $matching->getNbResults()) {
                    $result[] = $activeCatalog;
                }
            }
        }

        return $result;
    }
}
