<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
    use Elastica\Query\AbstractQuery;
use Elastica\Query\MatchQuery;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ProductNameRule implements RuleInterface
{
    private LocaleContextInterface $localeContext;

    private ConcatedNameResolverInterface $productNameNameResolver;

    public function __construct(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver,
    ) {
        $this->localeContext = $localeContext;
        $this->productNameNameResolver = $productNameNameResolver;
    }

    public function createSubquery(array $configuration): AbstractQuery
    {
        $name = $configuration['catalogName'];
        $localeCode = $this->localeContext->getLocaleCode();
        $propertyName = $this->productNameNameResolver->resolvePropertyName($localeCode);

        $nameQuery = new MatchQuery($propertyName, $name);
        $nameQuery->setFieldQuery($propertyName, $name);

        /* @phpstan-ignore-next-line Elastica\Query\MatchQuery Class extended by Elastica\Query\AbstractQuery*/
        return $nameQuery;
    }
}
