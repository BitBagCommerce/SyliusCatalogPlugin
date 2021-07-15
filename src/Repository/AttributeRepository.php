<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class AttributeRepository extends EntityRepository implements AttributeRepositoryInterface
{
    public function findByCodePart(string $code, ?int $limit = null): array
    {
        $qb = $this
            ->createQueryBuilder('o')
            ->select('o.id', 'o.code')
            ->andWhere('o.code LIKE :code')
            ->setParameter('code', '%' . $code . '%')
        ;

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
