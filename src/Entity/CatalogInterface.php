<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface CatalogInterface extends ResourceInterface, TranslatableInterface, CodeAwareInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getCode(): ?string;

    public function setCode(?string $code): void;

    public function getStartDate(): ?\DateTime;

    public function setStartDate(?\DateTime $startDate): void;

    public function getEndDate(): ?\DateTime;

    public function setEndDate(?\DateTime $endDate): void;

    public function getRules(): Collection;

    public function hasRules(): bool;

    public function hasRule(CatalogRuleInterface $rule): bool;

    public function addRule(CatalogRuleInterface $rule): void;

    public function removeRule(CatalogRuleInterface $rule): void;

    public function setConnectingRules(?string $connectingRules): void;

    public function getConnectingRules(): ?string;

    public function getProductAssociationRules(): Collection;

    public function getProductAssociationConnectingRules(): ?string;

    public function getTemplate(): ?string;

    public function setTemplate(?string $template): void;

    public function getDisplayProducts(): ?int;

    public function setDisplayProducts(?int $displayProducts): void;

    public function getSortingType(): ?string;

    public function setSortingType(?string $sortingType): void;
}
