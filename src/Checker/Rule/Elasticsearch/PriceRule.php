<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use BitBag\SyliusCatalogPlugin\Form\Type\FirstVariantPriceConfigurationType;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Range;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class PriceRule implements RuleInterface
{
    private ChannelContextInterface $channelContext;

    private ConcatedNameResolverInterface $propertyNameResolver;

    public function __construct(
        ConcatedNameResolverInterface $propertyNameResolver,
        ChannelContextInterface $channelContext
    ) {
        $this->channelContext = $channelContext;
        $this->propertyNameResolver = $propertyNameResolver;
    }

    public function createSubquery(array $configuration): AbstractQuery
    {
        /** @var string|null $currentChannel */
        $currentChannel = $this->channelContext->getChannel()->getCode();
        $price = $configuration['price'][$currentChannel]['amount'];

        switch ($configuration['operator']) {
            case FirstVariantPriceConfigurationType::OPERATOR_GT:
                $minPrice = (int) $price + 1;
                $maxPrice = \PHP_INT_MAX;

                break;
            case FirstVariantPriceConfigurationType::OPERATOR_GTE:
                $minPrice = (int) $price;
                $maxPrice = \PHP_INT_MAX;

                break;
            case FirstVariantPriceConfigurationType::OPERATOR_LT:
                $minPrice = 0;
                $maxPrice = (int) $price - 1;

                break;
            case FirstVariantPriceConfigurationType::OPERATOR_LTE:
                $minPrice = 0;
                $maxPrice = (int) $price;

                break;
            default:
                throw new \InvalidArgumentException('Unknown operator type.');
        }

        $rangeQuery = new Range();
        $rangeQuery->setParam($this->propertyNameResolver->resolvePropertyName($currentChannel), [
            'gte' => $minPrice,
            'lte' => $maxPrice,
        ]);

        return $rangeQuery;
    }
}
