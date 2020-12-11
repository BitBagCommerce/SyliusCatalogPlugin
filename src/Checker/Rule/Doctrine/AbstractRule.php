<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRule
{
    protected function addRule(string $connectingRules, QueryBuilder $queryBuilder, $rule): void
    {
        switch ($connectingRules) {
            case RuleCheckerInterface:: AND:
                $queryBuilder->andWhere($rule);

                break;
            case RuleCheckerInterface:: OR:
                $queryBuilder->orWhere($rule);

                break;
            default:
                throw new \InvalidArgumentException('Invalid connecting rule');
        }
    }
}
