<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Term;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class AttributeRule implements RuleInterface
{
    private StringFormatterInterface $stringFormatter;

    private LocaleContextInterface $localeContext;

    private ConcatedNameResolverInterface $attributeNameResolver;

    public function __construct(
        StringFormatterInterface $stringFormatter,
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $attributeNameResolver
    ) {
        $this->stringFormatter = $stringFormatter;
        $this->localeContext = $localeContext;
        $this->attributeNameResolver = $attributeNameResolver;
    }

    /* @phpstan-ignore-next-line Elastica\Query\Term Class extended by Elastica\Query\AbstractQuery*/
    public function createSubquery(array $configuration): AbstractQuery
    {
        /** @var AttributeInterface $attribute */
        $attribute = $configuration['attribute'];

        $paramName = \sprintf(
            '%s_%s.keyword',
            $this->attributeNameResolver->resolvePropertyName($attribute->getCode()),
            $this->localeContext->getLocaleCode()
        );
        $value = $this->stringFormatter->formatToLowercaseWithoutSpaces($configuration['value']);

        $term = new Term();
        $term->setTerm($paramName, $value);

        return $term;
    }
}
