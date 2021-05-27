<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use BitBag\SyliusCatalogPlugin\Form\Type\PriceConfigurationType;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class PriceRule extends AbstractRule
{
    private int $i = 0;

    private ChannelContextInterface $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $priceParameter = $this->generateParameterName();
        $channelCodeParameter = $this->generateParameterName();

        /** @var string|null $currentChannel */
        $currentChannel = $this->channelContext->getChannel()->getCode();

        $this->addRule(
            $connectingRules,
            $queryBuilder,
            $this->createFromFromOperator($configuration['operator'], $queryBuilder, $channelCodeParameter, $priceParameter)
        );

        $queryBuilder
            ->setParameter($priceParameter, $configuration['price'][$currentChannel]['amount'])
            ->setParameter($channelCodeParameter, $currentChannel)
        ;
    }

    private function generateParameterName(): string
    {
        return 'productPriceHigher' . $this->i++;
    }

    private function anyVariantRule(QueryBuilder $queryBuilder, string $channelCodeParameter, string $subqueryOperator, string $priceParameter): Func
    {
        $productVariantAlias = sprintf('pv%d', $this->i++);
        $channelPricingAlias = sprintf('cp%d', $this->i++);

        $subquery = $queryBuilder->getEntityManager()->createQueryBuilder()
            ->select(sprintf('%s.price', $channelPricingAlias))
            ->from(ProductVariantInterface::class, $productVariantAlias)
            ->join(sprintf('%s.channelPricings', $productVariantAlias), $channelPricingAlias)
            ->where(sprintf('%s.product = p', $productVariantAlias))
            ->andWhere(sprintf('%s.channelCode = :%s', $channelPricingAlias, $channelCodeParameter))
            ->andWhere(sprintf('%s.price %s :%s', $channelPricingAlias, $subqueryOperator, $priceParameter))
            ->getQuery();

        return $queryBuilder->expr()->exists($subquery->getDQL());
    }

    private function allVariantsRule(QueryBuilder $queryBuilder, string $channelCodeParameter, string $subqueryOperator, string $priceParameter): Func
    {
        $productVariantAlias = sprintf('pv%d', $this->i++);
        $channelPricingAlias = sprintf('cp%d', $this->i++);
        $subquery = $queryBuilder->getEntityManager()->createQueryBuilder()
            ->select(sprintf('%s.price', $channelPricingAlias))
            ->from(ProductVariantInterface::class, $productVariantAlias)
            ->join(sprintf('%s.channelPricings', $productVariantAlias), $channelPricingAlias)
            ->where(sprintf('%s.product = p', $productVariantAlias))
            ->andWhere(sprintf('%s.channelCode = :%s', $channelPricingAlias, $channelCodeParameter))
            ->andWhere(sprintf('%s.price %s :%s', $channelPricingAlias, $subqueryOperator, $priceParameter))
            ->getQuery();

        return $queryBuilder->expr()
            ->not($queryBuilder->expr()->exists($subquery->getDQL()));
    }

    private function createFromFromOperator(string $operator, QueryBuilder $queryBuilder, string $channelCodeParameter, string $priceParameter): Func
    {
        switch ($operator) {
            case PriceConfigurationType::OPERATOR_ALL_GT:
                return $this->allVariantsRule($queryBuilder, $channelCodeParameter, '<=', $priceParameter);
            case PriceConfigurationType::OPERATOR_ALL_GTE:
                return $this->allVariantsRule($queryBuilder, $channelCodeParameter, '<', $priceParameter);
            case PriceConfigurationType::OPERATOR_ALL_LT:
                return $this->allVariantsRule($queryBuilder, $channelCodeParameter, '>=', $priceParameter);
            case PriceConfigurationType::OPERATOR_ALL_LTE:
                return $this->allVariantsRule($queryBuilder, $channelCodeParameter, '>', $priceParameter);
            case PriceConfigurationType::OPERATOR_ANY_GT:
                return $this->anyVariantRule($queryBuilder, $channelCodeParameter, '>', $priceParameter);
            case PriceConfigurationType::OPERATOR_ANY_GTE:
                return $this->anyVariantRule($queryBuilder, $channelCodeParameter, '>=', $priceParameter);
            case PriceConfigurationType::OPERATOR_ANY_LT:
                return $this->anyVariantRule($queryBuilder, $channelCodeParameter, '<', $priceParameter);
            case PriceConfigurationType::OPERATOR_ANY_LTE:
                return $this->anyVariantRule($queryBuilder, $channelCodeParameter, '<=', $priceParameter);
        }

        throw new InvalidArgumentException('Unknown operator');
    }
}
