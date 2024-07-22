<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Sort\Elasticsearch;

use Elastica\Query;
use Elastica\Query\BoolQuery;

interface SortInterface
{
    public const DESC = 'DESC';

    public const ASC = 'ASC';

    public function modifyQueryBuilder(BoolQuery $boolQuery): Query;
}
