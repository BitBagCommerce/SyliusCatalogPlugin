<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms;
use Sylius\Component\Core\Model\Taxon;

final class TaxonRule implements RuleInterface
{
    private string $taxonsProperty;

    public function __construct(string $taxonsProperty)
    {
        $this->taxonsProperty = $taxonsProperty;
    }

    public function createSubquery(array $configuration): AbstractQuery
    {
        $taxonsCodes = array_map(
            function (Taxon $taxon) {
                return $taxon->getCode();
            },
            $configuration['taxons']->toArray()
        );

        /* @phpstan-ignore-next-line Elastica\Query\Terms Class extended by Elastica\Query\AbstractQuery*/
        return new Terms($this->taxonsProperty, $taxonsCodes);
    }
}
