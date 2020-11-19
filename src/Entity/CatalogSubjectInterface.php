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

use Doctrine\Common\Collections\Collection;

interface CatalogSubjectInterface
{
    public function getCatalogSubjectTotal(): int;

    /**
     * @return Collection|CatalogInterface[]
     *
     * @psalm-return Collection<array-key, CatalogInterface>
     */
    public function getCatalogs(): Collection;

    public function hasCatalog(CatalogInterface $catalog): bool;

    public function addCatalog(CatalogInterface $catalog): void;

    public function removeCatalog(CatalogInterface $catalog): void;
}
