<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Repository;

interface CatalogRepositoryInterface
{
    /**
     * @return Catalog[]
     */
    public function findActive(\DateTimeImmutable $on): array;
}
