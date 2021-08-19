<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRule implements RuleInterface
{
    /**
     * @param Expr|Func|Comparison $rule
     */
    protected function addRule(string $connectingRules, QueryBuilder $queryBuilder, $rule): void
    {
        switch ($connectingRules) {
            case RuleInterface::AND:
                $queryBuilder->andWhere($rule);

                break;
            case RuleInterface::OR:
                $queryBuilder->orWhere($rule);

                break;
            default:
                throw new \InvalidArgumentException('Invalid connecting rule');
        }
    }
}
