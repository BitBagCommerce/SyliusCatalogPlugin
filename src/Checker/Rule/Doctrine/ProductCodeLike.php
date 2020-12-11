<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */
declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use Doctrine\ORM\QueryBuilder;

class ProductCodeLike extends AbstractRule implements RuleCheckerInterface
{
    public const PRODUCT_ALIAS = 'p';

    public const OPERATOR_PREFIX = 'prefix';

    public const OPERATOR_LIKE = 'like';

    public const OPERATOR_SUFFIX = 'suffix';

    public const OPERATOR_EXACT = 'exact';

    /** @var int $i */
    private $i = 0;

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $parameterName = $this->generateParameterName();

        $rule = $queryBuilder->expr()
            ->like(sprintf('%s.code', self::PRODUCT_ALIAS), ':' . $parameterName);

        $this->addRule($connectingRules, $queryBuilder, $rule);

        switch ($configuration['operator']) {
            case self::OPERATOR_LIKE:
                $parameterValue = '%' . $configuration['productCodePhrase'] . '%';

                break;
            case self::OPERATOR_SUFFIX:
                $parameterValue = '%' . $configuration['productCodePhrase'];

                break;
            case self::OPERATOR_EXACT:
                $parameterValue = $configuration['productCodePhrase'];

                break;
            case self::OPERATOR_PREFIX:
                $parameterValue = $configuration['productCodePhrase'] . '%';

                break;
        }

        $queryBuilder
            ->setParameter($parameterName, $parameterValue);
    }

    private function generateParameterName(): string
    {
        return 'productCodeLike' . $this->i++;
    }
}
