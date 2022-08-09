<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
            $qb->expr()->isNull('o.startDate')
        );

        $endsQuery = $qb->expr()->orX(
            $qb->expr()->gte('o.endDate', ':on'),
            $qb->expr()->isNull('o.endDate')
        );

        return $qb
            ->andWhere($startsQuery)
            ->andWhere($endsQuery)
            ->setParameter('on', $on)
            ->getQuery()
            ->getResult();
    }
}
