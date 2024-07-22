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
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CatalogRepository extends EntityRepository implements CatalogRepositoryInterface
{
    /**
     * @return Catalog[]
     */
    public function findActive(\DateTimeImmutable $on): array
    {
        $qb = $this->createQueryBuilder('o');

        $startsQuery = $qb->expr()->orX(
            $qb->expr()->lte('o.startDate', ':on'),
            $qb->expr()->isNull('o.startDate'),
        );

        $endsQuery = $qb->expr()->orX(
            $qb->expr()->gte('o.endDate', ':on'),
            $qb->expr()->isNull('o.endDate'),
        );

        return $qb
            ->andWhere($startsQuery)
            ->andWhere($endsQuery)
            ->setParameter('on', $on)
            ->getQuery()
            ->getResult();
    }
}
