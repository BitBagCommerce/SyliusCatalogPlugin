<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Sort\Elasticsearch;

use Elastica\Query;
use Elastica\Query\BoolQuery;

final class NewestSort implements SortInterface
{
    private string $productCreatedAdProperty;

    public function __construct(string $productCreatedAdProperty)
    {
        $this->productCreatedAdProperty = $productCreatedAdProperty;
    }

    public function modifyQueryBuilder(BoolQuery $boolQuery): Query
    {
        $query = new Query($boolQuery);
        $query->addSort([$this->productCreatedAdProperty => self::DESC]);

        return $query;
    }
}
