<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    private FactoryInterface $catalogFactory;

    private CatalogRepositoryInterface $catalogRepository;

    private FactoryInterface $catalogTranslationFactory;

    private FactoryInterface $catalogRuleFactory;

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

    private function createRule(
        $rule,
        string $ruleTarget,
        CatalogInterface $catalog
    ): void
    {
        /** @var CatalogRuleInterface $catalogRule */
        $catalogRule = $this->catalogRuleFactory->createNew();
        $catalogRule->setConfiguration($rule['config']);
        $catalogRule->setType($rule['type']);
        $catalogRule->setTarget($ruleTarget);

        $catalog->addRule($catalogRule);
    }
}
