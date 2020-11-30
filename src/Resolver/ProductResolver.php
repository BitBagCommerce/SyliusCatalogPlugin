<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Entity\Catalog;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRule;
use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use BitBag\SyliusCatalogPlugin\Repository\ProductRepository;
use Sylius\Component\Registry\ServiceRegistry;
use function Amp\Promise\rethrow;

class ProductResolver implements ProductResolverInterface
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ServiceRegistry */
    private $serviceRegistry;

    public function __construct(ProductRepository $productRepository, ServiceRegistry  $serviceRegistry)

    {
        $this->serviceRegistry = $serviceRegistry;
        $this->productRepository = $productRepository;
    }

    public function findMatchingProducts(?string $code, $catalog)
    {
        $connectingRules = $catalog->getConnectingRules();

        /** @var CatalogRule $rules */
        $rules = $catalog->getRules();

        $qb = $this->productRepository->createQueryBuilder('p')
            ->leftJoin('p.translations', 'name')
            ->leftJoin('p.attributes', 'atr')
            ->leftJoin('p.variants', 'variant')
            ->leftJoin('variant.channelPricings', 'price');
        foreach ($rules as $rule) {
            $type = $rule->getType();

            /** @var RuleCheckerInterface $ruleChecker */
            $ruleChecker = $this->serviceRegistry->get($type);

            $sortByCodeConfiguration = $rule->getConfiguration();

            $ruleChecker->modifyQueryBuilder($sortByCodeConfiguration, $qb, $connectingRules);
        }
        return $qb
            ->getQuery()->getResult();


    }
}
