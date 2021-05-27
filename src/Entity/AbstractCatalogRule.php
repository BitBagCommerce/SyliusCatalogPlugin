<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Entity;

abstract class AbstractCatalogRule
{
    protected ?int $id;

    protected ?string $type;

    protected array $configuration = [];

    protected ?CatalogInterface $catalog;

    protected ?string $target;

    public function __construct()
    {
        $this->id = null;
        $this->type = null;
        $this->target = null;
        $this->configuration = [];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): void
    {
        $this->target = $target;
    }
}
