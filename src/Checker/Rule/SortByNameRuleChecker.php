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

class SortByNameRuleChecker implements RuleCheckerInterface
{
    /** @var int $i */
    private $i = 0;

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder): void
    {
        $parameterName = 'configuration'.$this->i;

        $this->i++;
        $queryBuilder
            ->leftJoin('p.translations', 'name'.$this->i)
            ->andWhere('name'.$this->i.'.name like :'.$parameterName)
            ->setParameter($parameterName, $configuration['catalogName'].'%');
    }
}
