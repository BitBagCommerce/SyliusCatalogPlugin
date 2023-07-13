<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\QueryBuilder;

use BitBag\SyliusCatalogPlugin\Entity\CatalogRuleInterface;
use Doctrine\Common\Collections\Collection;
use Elastica\Query\BoolQuery;

interface ProductQueryBuilderInterface
{
    public const MUST_NOT = 0;

    public const MUST = 1;

    /**
     * @param string $connectingRules
     * @param Collection<int, CatalogRuleInterface> $rules
     * @return BoolQuery
     */
    public function findMatchingProductsQuery(string $connectingRules, Collection $rules): BoolQuery;
}
