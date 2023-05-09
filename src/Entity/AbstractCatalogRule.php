<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

    protected bool $isNegation = false;

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

    public function isNegation(): bool
    {
        return $this->isNegation;
    }

    public function setIsNegation(bool $isNegation): void
    {
        $this->isNegation = $isNegation;
    }
}
