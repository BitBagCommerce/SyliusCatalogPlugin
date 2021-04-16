<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Fixture\Factory;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRule;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRuleInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogTranslationInterface;
use BitBag\SyliusCatalogPlugin\Repository\CatalogRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class CatalogFixtureFactory
{
    /** @var FactoryInterface */
    private $catalogFactory;

    /** @var CatalogRepositoryInterface */
    private $catalogRepository;

    /** @var FactoryInterface */
    private $catalogTranslationFactory;

    /** @var FactoryInterface */
    private $catalogRuleFactory;

    public function __construct(
        FactoryInterface $catalogFactory,
        FactoryInterface $catalogTranslationFactory,
        FactoryInterface $catalogRuleFactory,
        CatalogRepositoryInterface $catalogRepository
    ) {
        $this->catalogFactory = $catalogFactory;
        $this->catalogRepository = $catalogRepository;
        $this->catalogTranslationFactory = $catalogTranslationFactory;
        $this->catalogRuleFactory = $catalogRuleFactory;
    }

    public function load(array $data): void
    {
        foreach ($data as $code => $fields) {
            $this->createCatalog($code, $fields);
        }
    }

    private function createCatalog(string $code, array $catalogData): void
    {
        /** @var CatalogInterface $catalog */
        $catalog = $this->catalogFactory->createNew();
        $catalog->setCode($code);

        if (!empty($catalogData['starts_at'])) {
            $catalog->setStartDate(new \DateTime($catalogData['starts_at']));
        }

        if (!empty($catalogData['ends_at'])) {
            $catalog->setEndDate(new \DateTime($catalogData['ends_at']));
        }

        if (!empty($catalogData['template'])) {
            $catalog->setTemplate($catalogData['template']);
        }

        foreach ($catalogData['translations'] as $localeCode => $translation) {
            /** @var CatalogTranslationInterface $catalogTranslation */
            $catalogTranslation = $this->catalogTranslationFactory->createNew();

            $catalogTranslation->setLocale($localeCode);
            $catalogTranslation->setName($translation['name']);
            $catalog->addTranslation($catalogTranslation);
        }

        $catalog->setConnectingRules($catalogData['rules_operator']);
        foreach ($catalogData['rules'] as $rule) {
            $this->createRule($rule, CatalogRule::TARGET_CATALOG, $catalog);
        }

        $catalog->setProductAssociationConnectingRules($catalogData['associated_products_rules_operator']);
        foreach ($catalogData['associated_products_rules'] as $rule) {
            $this->createRule($rule, CatalogRule::TARGET_PRODUCT_ASSOCIATION, $catalog);
        }

        $catalog->setSortingType(($catalogData['sorting_type']));
        $catalog->setDisplayProducts(($catalogData['display_products']));

        $this->catalogRepository->add($catalog);
    }

    private function createRule($rule, string $ruleTarget, CatalogInterface $catalog): void
    {
        /** @var CatalogRuleInterface $catalogRule */
        $catalogRule = $this->catalogRuleFactory->createNew();
        $catalogRule->setConfiguration($rule['config']);
        $catalogRule->setType($rule['type']);
        $catalogRule->setTarget($ruleTarget);

        $catalog->addRule($catalogRule);
    }
}
