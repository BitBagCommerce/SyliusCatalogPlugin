<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\RuleInterface;
use BitBag\SyliusCatalogPlugin\Checker\Sort\Doctrine\SortInterface;
use BitBag\SyliusCatalogPlugin\Entity\AbstractCatalogRule;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRuleInterface;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductRepository;
use Sylius\Component\Registry\ServiceRegistry;

class ProductsInsideCatalogResolver implements ProductsInsideCatalogResolverInterface
{
    private ProductRepository $productRepository;

    private ServiceRegistry $ruleServiceRegistry;

    private ServiceRegistry $sortServiceRegistry;

    public function __construct(
        ProductRepository $productRepository,
        ServiceRegistry $ruleServiceRegistry,
        ServiceRegistry $sortServiceRegistry
    ) {
        $this->ruleServiceRegistry = $ruleServiceRegistry;
        $this->productRepository = $productRepository;
        $this->sortServiceRegistry = $sortServiceRegistry;
    }

    public function findMatchingProducts(CatalogInterface $catalog): array
    {
        $connectingRules = $catalog->getConnectingRules();

        /** @var AbstractCatalogRule $rules */
        $rules = $catalog->getRules();

        $qb = $this->productRepository->createQueryBuilder('p')
            ->addSelect('min(price) AS HIDDEN min_price')
            ->addSelect('max(price) AS HIDDEN max_price')
            ->addGroupBy('p')
            ->leftJoin('p.translations', 'name')
            ->leftJoin('p.variants', 'variant')
            ->leftJoin('p.productTaxons', 'productTaxon')
            ->leftJoin('productTaxon.taxon', 'taxon')
            ->leftJoin('variant.channelPricings', 'price')
        ;

        /** @var CatalogRuleInterface $rule */
        foreach ($rules as $rule) {
            $type = $rule->getType();

            /** @var RuleInterface $ruleChecker */
            $ruleChecker = $this->ruleServiceRegistry->get($type);

            $ruleConfiguration = $rule->getConfiguration();

            $ruleChecker->modifyQueryBuilder($ruleConfiguration, $qb, $connectingRules);
        }

        /** @var SortInterface $sortChecker */
        $sortChecker = $this->sortServiceRegistry->get($catalog->getSortingType());
        $sortChecker->modifyQueryBuilder($qb);

        $qb->setMaxResults($catalog->getDisplayProducts());

        return $qb->getQuery()->getResult();
    }
}
