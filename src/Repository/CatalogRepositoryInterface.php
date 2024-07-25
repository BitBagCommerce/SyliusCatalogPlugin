<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Repository;

use BitBag\SyliusCatalogPlugin\Entity\Catalog;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface CatalogRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Catalog[]
     */
    public function findActive(\DateTimeImmutable $on): array;
}
