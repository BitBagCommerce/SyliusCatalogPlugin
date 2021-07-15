<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AttributeRepositoryInterface extends RepositoryInterface
{
    public function findByCodePart(string $code, ?int $limit = null): array;
}
