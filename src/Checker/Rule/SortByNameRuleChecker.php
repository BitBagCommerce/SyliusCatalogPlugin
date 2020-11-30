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
use Sylius\Component\Locale\Context\LocaleContextInterface;

class SortByNameRuleChecker implements RuleCheckerInterface
{
    /** @var int $i */
    private $i = 0;

    /** @var LocaleContextInterface */
    private $localeContext;

    public function __construct(LocaleContextInterface $localeContext)
    {
        $this-> localeContext = $localeContext;
    }

    public function modifyQueryBuilder(array $configuration, QueryBuilder $queryBuilder, string $connectingRules): void
    {
        $parameterName = 'configurationName'.$this->i;
        $locale = $this->localeContext->getLocaleCode();
        $this->i++;

        if ($connectingRules === self::OR) {
            $queryBuilder
                ->andWhere('atr.localeCode =:locale')
                ->orWhere('name.name like :'.$parameterName);
        } else {
            $queryBuilder
                ->andWhere('name.name like :'.$parameterName)
                ->andWhere('atr.localeCode =:locale');
        }

        $queryBuilder
            ->setParameter($parameterName, $configuration['catalogName'].'%')
            ->setParameter('locale', $locale);
    }
}
