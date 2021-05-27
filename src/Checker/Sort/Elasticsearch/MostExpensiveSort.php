<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
