<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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

        $taxonQuery = new Terms();
        $taxonQuery->setTerms($this->taxonsProperty, $taxonsCodes);

        return $taxonQuery;
    }
}
