<?php

namespace BitBag\SyliusCatalogPlugin\Notifier;

use BitBag\SyliusCatalogPlugin\Events\QueryCreatedEventInterface;
use BitBag\SyliusCatalogPlugin\Factory\QueryCreatedEventFactoryInterface;
use Elastica\Query\AbstractQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface QueryDispatcherInterface
{
    public function dispatchNewQuery(AbstractQuery $boolQuery): QueryCreatedEventInterface;
}
