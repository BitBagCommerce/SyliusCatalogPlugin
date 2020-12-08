<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule;


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
