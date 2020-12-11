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

interface CatalogRuleInterface extends ResourceInterface, ConfigurableCatalogElementInterface
{
    public const TARGET_CATALOG = 'catalog';

    public const TARGET_PRODUCT_ASSOCIATION = 'product_association';

    public function setType(?string $type): void;

    public function setConfiguration(array $configuration): void;

    public function setCatalog(?CatalogInterface $catalog): void;

    public function getTarget(): ?string;

    public function setTarget(?string $target): void;
}
