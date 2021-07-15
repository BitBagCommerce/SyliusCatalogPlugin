<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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

    public function createSubquery(array $configuration): AbstractQuery
    {
        /** @var AttributeInterface $attribute */
        $attribute = $configuration['attribute'];

        $paramName = \sprintf('%s_%s.keyword',
            $this->attributeNameResolver->resolvePropertyName($attribute->getCode()),
            $this->localeContext->getLocaleCode()
        );
        $value = $this->stringFormatter->formatToLowercaseWithoutSpaces($configuration['value']);

        $term = new Term();
        $term->setTerm($paramName, $value);

        return $term;
    }
}
