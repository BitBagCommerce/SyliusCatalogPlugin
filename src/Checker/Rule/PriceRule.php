<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule;

use App\Entity\Product\ProductVariant;
use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Channel\Context\ChannelContextInterface;

class PriceRule extends AbstractRule implements RuleCheckerInterface
{
    /** @var int $i */
    private $i = 0;

    public const OPERATOR_ALL_GTE = 'all_gte';

    public const OPERATOR_ALL_GT = 'all_gt';

    public const OPERATOR_ALL_LT = 'all_lt';

    public const OPERATOR_ALL_LTE = 'all_lte';

    public const OPERATOR_ANY_GTE = 'any_gte';

    public const OPERATOR_ANY_GT = 'any_gt';

    public const OPERATOR_ANY_LT = 'any_lt';

    public const OPERATOR_ANY_LTE = 'any_lte';

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $priceParameter = $this->generateParameterName();
        $channelCodeParameter = $this->generateParameterName();

        /** @var ChannelContextInterface $currentChannel */
        $currentChannel = $this->channelContext->getChannel()->getCode();

        $subquery = $queryBuilder->getEntityManager()->createQueryBuilder()
            ->select('cp.price')
            ->from(ProductVariant::class, 'pv')
            ->join('pv.channelPricings', 'cp')
            ->where('pv.product = p')
            ->andWhere('cp.channelCode = :' . $channelCodeParameter)
            ->andWhere('cp.price <= :' . $priceParameter)

            ->getQuery();

        $rule = $queryBuilder->expr()
            ->not($queryBuilder->expr()->exists($subquery->getDQL()))
        ;
        $this->addRule($connectingRules, $queryBuilder, $rule);

        $queryBuilder
            ->setParameter($priceParameter, $configuration[$currentChannel]['price'])
            ->setParameter($channelCodeParameter, $currentChannel)

        ;
    }

    private function generateParameterName(): string
    {
        return 'productPriceHigher' . $this->i++;
    }
}
