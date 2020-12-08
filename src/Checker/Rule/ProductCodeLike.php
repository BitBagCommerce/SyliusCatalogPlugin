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

class ProductCodeLike extends AbstractRule implements RuleCheckerInterface
{
    const PRODUCT_ALIAS = 'p';

    /** @var int $i */
    private $i = 0;

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $parameterName = $this->generateParameterName();

        $rule = $queryBuilder->expr()
            ->like(sprintf("%s.code", self::PRODUCT_ALIAS), ':'.$parameterName);

        $this->addRule($connectingRules, $queryBuilder, $rule);

        $queryBuilder
            ->setParameter($parameterName, '%' . $configuration['productCodePrefix'] . '%');
    }

    private function generateParameterName(): string
    {
        return 'productCodeLike' . $this->i++;
    }

}
