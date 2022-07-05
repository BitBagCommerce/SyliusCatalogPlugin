<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use Doctrine\ORM\QueryBuilder;

final class ProductCodeLike extends AbstractRule implements RuleInterface
{
    public const PRODUCT_ALIAS = 'p';

    public const OPERATOR_PREFIX = 'prefix';

    public const OPERATOR_LIKE = 'like';

    public const OPERATOR_SUFFIX = 'suffix';

    public const OPERATOR_EXACT = 'exact';

    private int $i = 0;

    public function modifyQueryBuilder(
        array $configuration,
        QueryBuilder $queryBuilder,
        string $connectingRules
    ): void {
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
            default:
                throw new \InvalidArgumentException('Unknown operator type.');
        }

        $queryBuilder
            ->setParameter($parameterName, $parameterValue);
    }

    private function generateParameterName(): string
    {
        return 'productCodeLike' . $this->i++;
    }
}
