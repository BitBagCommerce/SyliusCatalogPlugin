<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
