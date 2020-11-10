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

    private  $id;

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
        return  $this->getCatalogTranslation();
    }

    protected function createTranslation(): CatalogTranslation
    {
        return new CatalogTranslation();
    }
}
