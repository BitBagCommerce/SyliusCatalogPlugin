<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\RuleInterface;
use BitBag\SyliusCatalogPlugin\Entity\AbstractCatalogRule;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Repository\CatalogRepositoryInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogsForProductResolver implements CatalogsForProductResolverInterface
{
    private RepositoryInterface $catalogRepository;

    private ServiceRegistry $serviceRegistry;

    private ProductRepositoryInterface $productRepository;

    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        ProductRepositoryInterface $productRepository,
        ServiceRegistry $serviceRegistry
    ) {
        $this->catalogRepository = $catalogRepository;
        $this->serviceRegistry = $serviceRegistry;
        $this->productRepository = $productRepository;
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
            $connectingRules = $activeCatalog->getConnectingRules();

            /** @var AbstractCatalogRule $rules */
            $rules = $activeCatalog->getProductAssociationRules();

            /** @var QueryBuilder $qb */
            $qb = $this->productRepository->createQueryBuilder('p')
                ->select('count(p.code)')
                ->leftJoin('p.translations', 'name')
                ->leftJoin('p.variants', 'variant')
                ->leftJoin('p.productTaxons', 'productTaxon')
                ->leftJoin('productTaxon.taxon', 'taxon')
                ->leftJoin('variant.channelPricings', 'price')
                ->andWhere('p.code = :productCode')
                ->setParameter('productCode', $product->getCode());

            foreach ($rules as $rule) {
                $type = $rule->getType();

                /** @var RuleInterface $ruleChecker */
                $ruleChecker = $this->serviceRegistry->get($type);

                $ruleConfiguration = $rule->getConfiguration();

                $ruleChecker->modifyQueryBuilder($ruleConfiguration, $qb, $connectingRules);
            }

            if (0 < $qb->getQuery()->getSingleScalarResult()) {
                $result[] = $activeCatalog;
            }
        }

        return $result;
    }
}
