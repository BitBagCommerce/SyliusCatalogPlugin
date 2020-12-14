<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\QueryBuilder;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;

interface ProductQueryBuilderInterface
{
    public function findMatchingProductsQuery(CatalogInterface $catalog);
}
