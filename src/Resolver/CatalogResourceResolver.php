<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use Psr\Log\LoggerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CatalogResourceResolver implements CatalogResourceResolverInterface
{
    private LoggerInterface $logger;

    private EntityRepository $catalogRepository;

    public function __construct(LoggerInterface $logger, EntityRepository $catalogRepository)
    {
        $this->logger = $logger;
        $this->catalogRepository = $catalogRepository;
    }

    public function findOrLog(?string $code): ?CatalogInterface
    {
        $catalog = $this->catalogRepository->findOneBy(['code' => $code]);

        if (false === $catalog instanceof CatalogInterface) {
            $this->logger->warning(sprintf(
                'Catalog with "%s" code was not found in the database.',
                $code
            ));

            return null;
        }

        return $catalog;
    }
}
