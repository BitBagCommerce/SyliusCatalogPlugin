<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine;

use BitBag\SyliusCatalogPlugin\Exception\AttributeStorageTypeNotFound;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class AttributeRule extends AbstractRule implements AttributeRuleInterface
{
    private int $i = 0;

    private LocaleContextInterface $localeContext;

    public function __construct(LocaleContextInterface $localeContext)
    {
        $this->localeContext = $localeContext;
    }

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        /** @var AttributeInterface $attribute */
        $attribute = $configuration['attribute'];

        if (0 === $this->i) {
            $queryBuilder->leftJoin('p.attributes', self::PRODUCT_ATTRIBUTES_ALIAS);
        }

        $rule = $this->buildRule($queryBuilder, $attribute, $configuration['value']);

        $this->addRule($connectingRules, $queryBuilder, $rule);
    }

    private function buildRule(QueryBuilder $queryBuilder, AttributeInterface $attribute, string $value): Andx
    {
        $valueFieldName = self::PRODUCT_ATTRIBUTES_ALIAS . '.' . $this->getAttributeStorageFieldName($attribute->getType());

        $attributeValueParamName = $this->generateAttributeValueParameterName();
        $localeCodeParameterName = $this->generateAttributeLocaleCodeParameterName();

        $conditions = $queryBuilder->expr()->andX();

        $conditions->add($queryBuilder->expr()->eq(self::PRODUCT_ATTRIBUTES_ALIAS . '.localeCode', ":{$localeCodeParameterName}"));

        if ($attribute->getType() !== SelectAttributeType::TYPE) {
            $conditions->add($queryBuilder->expr()->eq($valueFieldName, ":{$attributeValueParamName}"));

            $queryBuilder->setParameter($attributeValueParamName, $value);
        } else {
            $conditions->add($queryBuilder->expr()->like($valueFieldName, ":{$attributeValueParamName}"));

            $queryBuilder
                ->setParameter(
                    $attributeValueParamName,
                    sprintf(self::SELECT_ATTRIBUTE_PATTERN,
                        $this->getValueHashKey(
                            $attribute->getConfiguration(),
                            $value
                        )
                    )
                )
            ;
        }

        $queryBuilder->setParameter($localeCodeParameterName, $this->localeContext->getLocaleCode());

        return $conditions;
    }

    private function generateAttributeValueParameterName(): string
    {
        return 'attributeValue' . $this->i++;
    }

    private function generateAttributeLocaleCodeParameterName(): string
    {
        return 'attributeLocaleCode' . $this->i++;
    }

    private function getAttributeStorageFieldName(?string $type): string
    {
        if (null === $type || !array_key_exists($type, self::ATTRIBUTE_STORAGE_FIELD_MAP)) {
            throw new AttributeStorageTypeNotFound(sprintf('Attribute with type `%s` storage field name not found.', $type));
        }

        return self::ATTRIBUTE_STORAGE_FIELD_MAP[$type];
    }

    private function getValueHashKey(array $configuration, string $searchValue): ?string
    {
        if (true === array_key_exists('choices', $configuration)) {
            foreach ($configuration['choices'] as $hashKey => $values) {
                foreach ($values as $value) {
                    if (strtolower($value) === strtolower($searchValue)) {
                        return $hashKey;
                    }
                }
            }
        }

        return null;
    }
}
