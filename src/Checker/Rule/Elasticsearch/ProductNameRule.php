<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */
declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Match;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ProductNameRule implements RuleInterface
{
    private LocaleContextInterface $localeContext;

    private ConcatedNameResolverInterface $productNameNameResolver;

    private string $namePropertyPrefix;

    public function __construct(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver,
        string $namePropertyPrefix
    ) {
        $this->localeContext = $localeContext;
        $this->productNameNameResolver = $productNameNameResolver;
        $this->namePropertyPrefix = $namePropertyPrefix;
    }

    public function createSubquery(array $configuration): AbstractQuery
    {
        $name = $configuration['catalogName'];
        $localeCode = $this->localeContext->getLocaleCode();
        $propertyName = $this->productNameNameResolver->resolvePropertyName($localeCode);

        $nameQuery = new Match();
        $nameQuery->setFieldQuery($propertyName, $name);

        return $nameQuery;
    }
}
