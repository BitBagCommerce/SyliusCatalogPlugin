<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Entity;

use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class Catalog implements CatalogInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    /** @var int|null */
    private  $id;

    /** @var  \DateTime|null */
    private $startDate;

    /** @var \DateTime|null */
    private $endDate;

    /**
     * @return \DateTime|null
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime|null $startDate
     */
    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime|null $endDate
     */
    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->getCatalogTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getCatalogTranslation()->setName($name);
    }

    /** @return CatalogTranslationInterface|TranslationInterface */
    protected function getCatalogTranslation(): TranslationInterface
    {
        return  $this->getTranslation();
    }

    protected function createTranslation(): CatalogTranslation
    {
        return new CatalogTranslation();
    }
}
