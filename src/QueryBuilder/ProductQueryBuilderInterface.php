<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
     * @param Collection<int, CatalogRuleInterface> $rules
     */
    public function findMatchingProductsQuery(string $connectingRules, Collection $rules): BoolQuery;
}
