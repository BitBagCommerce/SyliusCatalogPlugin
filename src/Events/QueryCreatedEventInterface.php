<?php

namespace BitBag\SyliusCatalogPlugin\Events;

use Elastica\Query\AbstractQuery;

interface QueryCreatedEventInterface
{
    public function getQuery(): AbstractQuery;
}
