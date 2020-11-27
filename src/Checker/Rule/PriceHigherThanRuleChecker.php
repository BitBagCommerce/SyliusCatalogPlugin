<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule;

use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use Doctrine\ORM\QueryBuilder;

class PriceHigherThanRuleChecker implements RuleCheckerInterface
{
    /** @var int $i */
    private $i = 0;

    public function ModifyQueryBuilder( array $configuration, QueryBuilder $queryBuilder): void
    {
        $parameterName = 'configuration'.$this->i;
        $this->i++;
        $queryBuilder
            ->leftJoin('p.variants', 'variant'.$this->i)
            ->leftJoin('variant'.$this->i.'.channelPricings', 'price'.$this->i)
            ->andWhere('price'.$this->i.'.price > :'.$parameterName)
            ->setParameter($parameterName, $configuration['FASHION_WEB']['amount']);
    }
}
