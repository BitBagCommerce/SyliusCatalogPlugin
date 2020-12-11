<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Resolver;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use Psr\Log\LoggerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CatalogResourceResolver implements CatalogResourceResolverInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var EntityRepository */
    private $catalogRepository;

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
