<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusCatalogPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\RuleInterface;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Repository\CatalogRepositoryInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Tests\BitBag\SyliusCatalogPlugin\Behat\Service\RandomStringGeneratorInterface;

final class CatalogContext implements Context
{
    private SharedStorageInterface $sharedStorage;

    private FactoryInterface $catalogFactory;

    private CatalogRepositoryInterface $catalogRepository;

    private RandomStringGeneratorInterface $randomStringGenerator;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $catalogFactory,
        CatalogRepositoryInterface $catalogRepository,
        RandomStringGeneratorInterface $randomStringGenerator
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->catalogFactory = $catalogFactory;
        $this->catalogRepository = $catalogRepository;
        $this->randomStringGenerator = $randomStringGenerator;
    }

    /**
     * @Given there is a catalog in the store
     */
    public function thereIsAnExistingCatalog(): void
    {
        $catalog = $this->createCatalog();

        $this->saveCatalog($catalog);
    }

    /**
     * @Given there is an existing catalog with :code code
     */
    public function thereIsAnExistingCatalogWithCode(string $code): void
    {
        $catalog = $this->createCatalog($code);

        $this->saveCatalog($catalog);
    }

    /**
     * @Given there is a :catalogName catalog in the store
     */
    public function thereIsACatalogInTheStore(string $catalogName): void
    {
        $catalog = $this->createCatalog(strtolower(StringInflector::nameToCode($catalogName)), $catalogName);

        $this->saveCatalog($catalog);
    }

    private function createCatalog(?string $code = null, string $name = null): CatalogInterface
    {
        /** @var CatalogInterface $catalog */
        $catalog = $this->catalogFactory->createNew();

        if (null === $code) {
            $code = $this->randomStringGenerator->generate();
        }

        if (null === $name) {
            $name = $this->randomStringGenerator->generate();
        }

        $catalog->setCode($code);
        $catalog->setCurrentLocale('en_US');
        $catalog->setName($name);
        $catalog->setTemplate('@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig');
        $catalog->setSortingType('newest');
        $catalog->setDisplayProducts(3);
        $catalog->setConnectingRules(RuleInterface::OR);
        $catalog->setProductAssociationConnectingRules(RuleInterface::OR);

        return $catalog;
    }

    private function saveCatalog(CatalogInterface $catalog): void
    {
        $this->catalogRepository->add($catalog);
        $this->sharedStorage->set('catalog', $catalog);
    }
}
