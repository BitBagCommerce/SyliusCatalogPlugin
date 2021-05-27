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
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ProductNameRule extends AbstractRule
{
    private int $i = 0;

    private LocaleContextInterface $localeContext;

    public function __construct(LocaleContextInterface $localeContext)
    {
        $this->localeContext = $localeContext;
    }

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $parameterName = 'configurationName' . $this->i;
        $locale = $this->localeContext->getLocaleCode();
        ++$this->i;

        if ($connectingRules === self::OR) {
            $queryBuilder
                ->andWhere('name.locale =:locale')
                ->orWhere('name.name like :' . $parameterName);
        } else {
            $queryBuilder
                ->andWhere('name.name like :' . $parameterName)
                ->andWhere('name.locale = :locale');
        }

        $queryBuilder
            ->setParameter($parameterName, $configuration['catalogName'] . '%')
            ->setParameter('locale', $locale);
    }
}
