<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Sort\Elasticsearch;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class MostExpensiveSort implements SortInterface
{
    private ConcatedNameResolverInterface $channelPricingNameResolver;

    private ChannelContextInterface $channelContext;

    public function __construct(
        ConcatedNameResolverInterface $channelPricingNameResolver,
        ChannelContextInterface $channelContext
    ) {
        $this->channelPricingNameResolver = $channelPricingNameResolver;
        $this->channelContext = $channelContext;
    }

    public function modifyQueryBuilder(BoolQuery $boolQuery): Query
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $propertyName = $this->channelPricingNameResolver->resolvePropertyName($channel->getCode());

        $query = new Query($boolQuery);
        $query->addSort([$propertyName => self::DESC]);

        return $query;
    }
}
