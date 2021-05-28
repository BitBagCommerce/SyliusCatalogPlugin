<?php

/*
* This file has been created by developers from BitBag.
* Feel free to contact us once you face any issues or want to start
* You can find more information about us on https://bitbag.io and write us
* an email on hello@bitbag.io.
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusCatalogPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\BitBag\SyliusCatalogPlugin\Behat\Page\Admin\Catalog\CreatePageInterface;
use Tests\BitBag\SyliusCatalogPlugin\Behat\Page\Admin\Catalog\IndexPageInterface;
use Tests\BitBag\SyliusCatalogPlugin\Behat\Page\Admin\Catalog\UpdatePageInterface;

final class CatalogContext implements Context
{
    private SharedStorageInterface $sharedStorage;

    private CurrentPageResolverInterface $currentPageResolver;

    private NotificationCheckerInterface $notificationChecker;

    private IndexPageInterface $indexPage;

    private CreatePageInterface $createPage;

    private UpdatePageInterface $updatePage;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        CurrentPageResolverInterface $currentPageResolver,
        NotificationCheckerInterface $notificationChecker,
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->currentPageResolver = $currentPageResolver;
        $this->notificationChecker = $notificationChecker;
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @When I go to the catalogs page
     */
    public function iGoToTheCatalogsPage(): void
    {
        $this->indexPage->open();
    }

    /**
     * @When I go to the create catalog page
     */
    public function iGoToTheCreateCatalogPage(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I delete this catalog
     */
    public function iDeleteThisCatalog(): void
    {
        /** @var CatalogInterface $catalog */
        $catalog = $this->sharedStorage->get('catalog');

        $this->indexPage->deleteCatalog($catalog->getName());
    }

    /**
     * @When I fill the code with :code
     */
    public function iFillTheCodeWith(string $code): void
    {
        $this->resolveCurrentPage()->fillCode($code);
    }

    /**
     * @When I fill the name with :name
     */
    public function iFillTheNameWith(string $name): void
    {
        $this->resolveCurrentPage()->fillName($name);
    }

    /**
     * @Then I should be notified that new catalog has been created
     */
    public function iShouldBeNotifiedThatNewCatalogHasBeenCreated(): void
    {
        $this->notificationChecker->checkNotification(
            'Catalog has been successfully created.',
            NotificationType::success()
        );
    }

    /**
     * @Then I should be notified that the catalog has been deleted
     */
    public function iShouldBeNotifiedThatTheCatalogHasBeenDeleted(): void
    {
        $this->notificationChecker->checkNotification(
            'Catalog has been successfully deleted.',
            NotificationType::success()
        );
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @Then I should see empty list of catalogs
     */
    public function iShouldSeeEmptyListOfCatalogs(): void
    {
        $this->resolveCurrentPage()->isEmpty();
    }

    /**
     * @return IndexPageInterface|CreatePageInterface|UpdatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->indexPage,
            $this->createPage,
            $this->updatePage,
        ]);
    }
}
