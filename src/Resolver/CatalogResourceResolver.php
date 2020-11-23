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
use BitBag\SyliusCatalogPlugin\Repository\ProductRepository;
use Psr\Log\LoggerInterface;

class CatalogResourceResolver implements CatalogResourceResolverInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository, LoggerInterface $logger)
    {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    public function findOrLog(?string $code): ?CatalogInterface
    {
        $product= $this->productRepository->findProducts($code);

        if (false === $product instanceof CatalogInterface) {
            $this->logger->warning(sprintf(
                'Product with "%s" code was not found in the database.',
                $code
            ));

            return null;
        }

        return $product;
    }
}
