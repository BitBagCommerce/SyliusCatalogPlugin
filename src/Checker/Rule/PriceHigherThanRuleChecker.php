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
use Sylius\Component\Channel\Context\ChannelContextInterface;

class PriceHigherThanRuleChecker implements RuleCheckerInterface
{
    /** @var int $i */
    private $i = 0;

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function modifyQueryBuilder( array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $parameterName = 'configurationPrice'.$this->i;
        $this->i++;

        /** @var ChannelContextInterface $currentChannel */
        $currentChannel = $this->channelContext->getChannel()->getCode();

        $queryBuilder

            ->andWhere('price.channelCode =:currentChannel')
            ->setParameter('currentChannel', $currentChannel);

        if ($connectingRules == self::OR) {
            $queryBuilder
                ->orWhere('price.price > :'.$parameterName);
        } else {
            $queryBuilder
                ->andWhere('price.price > :'.$parameterName);
        }
        $queryBuilder
            ->setParameter($parameterName, $configuration['FASHION_WEB']['amount']);
    }
}
