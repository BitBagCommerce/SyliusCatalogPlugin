<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Channel\Context\ChannelContextInterface;

class TaxonRule extends AbstractRule
{
    private int $i = 0;

    private ChannelContextInterface $channelContext;

    public function __construct(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    public function modifyQueryBuilder(
        array $configuration,
        QueryBuilder $queryBuilder,
        string $connectingRules
    ): void
    {
        $taxonsParameterName = $this->generateParameterName();

        $rule = $queryBuilder->expr()
            ->in('productTaxon.taxon', ":{$taxonsParameterName}")
        ;
        $this->addRule($connectingRules, $queryBuilder, $rule);

        $queryBuilder->setParameter($taxonsParameterName, $configuration['taxons']);
    }

    private function generateParameterName(): string
    {
        return 'taxonRule' . $this->i++;
    }
}
