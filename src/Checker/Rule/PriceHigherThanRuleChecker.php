<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule;

use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Channel\Context\RequestBased\ChannelContext;

class PriceHigherThanRuleChecker implements RuleCheckerInterface
{
    /** @var int $i */
    private $i = 0;

    /** @var ChannelContext */
    private $channelContext;

    public function __construct(ChannelContext $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function ModifyQueryBuilder( array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $parameterName = 'configuration'.$this->i;
        $this->i++;

        /** @var ChannelContext $currentChannel */
        $currentChannel = $this->channelContext->getChannel()->getCode();

        $queryBuilder
            ->leftJoin('p.variants', 'variant'.$this->i)
            ->leftJoin('variant'.$this->i.'.channelPricings', 'price'.$this->i)
            ->andWhere('price.channel_code =:currentChannel')
            ->setParameter('currentChannel', $currentChannel);

        if ($connectingRules == "Or") {
            $queryBuilder
                ->orWhere('price'.$this->i.'.price > :'.$parameterName);
        } else {
            $queryBuilder
            ->andWhere('price'.$this->i.'.price > :'.$parameterName);
        }
        $queryBuilder
            ->setParameter($parameterName, $configuration['FASHION_WEB']['amount']);
    }
}
