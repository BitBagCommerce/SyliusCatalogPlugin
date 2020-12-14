<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */
declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductsByPartialNameQueryBuilder;
use Elastica\Query\AbstractQuery;

final class ProductNameRule implements RuleInterface
{
    /** @var ProductsByPartialNameQueryBuilder */
    private $byPartialNameQueryBuilder;

    public function __construct(ProductsByPartialNameQueryBuilder $byPartialNameQueryBuilder)
    {
        $this->byPartialNameQueryBuilder = $byPartialNameQueryBuilder;
    }

    public function modifyQueryBuilder(array $configuration): AbstractQuery
    {
        return $this->byPartialNameQueryBuilder->buildQuery(['name' => $configuration['catalogName']]);
    }
}
