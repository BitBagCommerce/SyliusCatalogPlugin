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
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRuleInterface;
use BitBag\SyliusCatalogPlugin\Repository\CatalogRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Registry\ServiceRegistry;

final class CatalogsForProductResolver implements CatalogsForProductResolverInterface
{
    private CatalogRepositoryInterface $catalogRepository;

    private ServiceRegistry $serviceRegistry;

    private ProductRepositoryInterface $productRepository;

    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        ProductRepositoryInterface $productRepository,
        ServiceRegistry $serviceRegistry,
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
            if (0 !== $activeCatalog->getProductAssociationRules()->count()) {
                $connectingRules = $activeCatalog->getConnectingRules();

                /** @var Collection<int, CatalogRuleInterface> $rules */
                $rules = $activeCatalog->getProductAssociationRules();

                $qb = $this->productRepository->createQueryBuilder('p') /** @phpstan-ignore-line ProductRepository inherits the createQueryBuilder method from EntityRepository*/
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

                    if (null === $type ||
                        null === $connectingRules) {
                        continue;
                    }

                    /** @var RuleInterface $ruleChecker */
                    $ruleChecker = $this->serviceRegistry->get($type);

                    $ruleConfiguration = $rule->getConfiguration();

                    $ruleChecker->modifyQueryBuilder($ruleConfiguration, $qb, $connectingRules);
                }

                if (0 < $qb->getQuery()->getSingleScalarResult()) {
                    $result[] = $activeCatalog;
                }
            }
        }

        return $result;
    }
}
