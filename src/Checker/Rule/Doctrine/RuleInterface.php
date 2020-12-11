<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Doctrine\ORM\QueryBuilder;

interface RuleInterface
{
    public const OR = 'Or';

    public const AND = 'And';

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void;
}
