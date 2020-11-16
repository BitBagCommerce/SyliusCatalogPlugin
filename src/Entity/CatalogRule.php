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

class CatalogRule implements CatalogRuleInterface
{
    /** @var mixed */
    protected $id;

    /** @var string|null */
    protected $type;

    /** @var array */
    protected $configuration = [];

    /** @var CatalogInterface|null */
    protected $catalog;

    public function getId()
    {
        return $this->type;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getCatalog(): ?CatalogInterface
    {
        return $this->catalog;
    }

    public function setCatalog(?CatalogInterface $catalog): void
    {
        $this->catalog = $catalog;
    }
}
