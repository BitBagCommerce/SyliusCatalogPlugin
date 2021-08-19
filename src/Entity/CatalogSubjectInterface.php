<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
