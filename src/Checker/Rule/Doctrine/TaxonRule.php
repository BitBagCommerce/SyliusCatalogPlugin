<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Doctrine\ORM\QueryBuilder;

class TaxonRule extends AbstractRule
{
    private int $i = 0;

    public function modifyQueryBuilder(
        array $configuration,
        QueryBuilder $queryBuilder,
        string $connectingRules,
    ): void {
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
