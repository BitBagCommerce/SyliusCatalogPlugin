<?php

namespace BitBag\SyliusCatalogPlugin\Factory;

use BitBag\SyliusCatalogPlugin\Events\QueryCreatedEventInterface;
use Elastica\Query\AbstractQuery;

interface QueryCreatedEventFactoryInterface
{
    public function createNewEvent(AbstractQuery $boolQuery): QueryCreatedEventInterface;
}
