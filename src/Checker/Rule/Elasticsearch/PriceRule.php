<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
