<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use Elastica\Query\AbstractQuery;

interface RuleInterface
{
    public const OR = 'Or';

    public const AND = 'And';

    public function modifyQueryBuilder(array $configuration): AbstractQuery;
}
