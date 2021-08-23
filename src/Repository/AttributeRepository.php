<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
