<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ConfigurableCatalogElementInterface extends ResourceInterface
{
    public function getType(): ?string;

    public function getConfiguration(): array;

    public function getCatalog(): ?CatalogInterface;
}
