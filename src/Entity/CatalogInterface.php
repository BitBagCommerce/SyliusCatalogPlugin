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

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface CatalogInterface extends ResourceInterface, TranslatableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getStartDate(): ?\DateTime;

    public function setStartDate(?\DateTime $startDate): void;

    public function getEndDate(): ?\DateTime;

    public function setEndDate(?\DateTime $endDate): void;

    public function getRules(): ?CatalogRuleInterface;

    public function setRules(?CatalogRuleInterface $rules): void;
}
