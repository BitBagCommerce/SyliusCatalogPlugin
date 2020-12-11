<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Entity\Catalog;
use Sylius\Component\Core\Model\ProductInterface;

interface ProductCatalogResolverInterface
{
    /**
     * @return Catalog[]
     */
    public function resolveProductCatalogs(ProductInterface $product, \DateTimeImmutable $dataTime): array;
}
