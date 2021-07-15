<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusCatalogPlugin\DataProvider;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogItemDataProviderSpec extends ObjectBehavior
{
    function let(RepositoryInterface $catalogRepository, ProductsInsideCatalogResolverInterface $productResolver): void
    {
        $this->beConstructedWith($catalogRepository, $productResolver);
    }

    function it_supports_only_catalog(): void
    {
        $this->supports(CatalogInterface::class, 'get')->shouldReturn(true);
    }

    function it_provides_catalog(
        RepositoryInterface $catalogRepository,
        ProductsInsideCatalogResolverInterface $productResolver,
        CatalogInterface $catalog,
        ProductInterface $product
    ) {
        $catalogRepository->findOneBy(['id' => 1])->willReturn($catalog);
        $productResolver->findMatchingProducts($catalog)->willReturn([$product]);
        $catalog->setProducts([$product])->shouldBeCalled();

        $this->getItem(CatalogInterface::class, '1')->shouldReturn($catalog);
    }

    function it_provides_returns_null(RepositoryInterface $catalogRepository)
    {
        $catalogRepository->findOneBy(['id' => 1])->willReturn(null);

        $this->getItem(CatalogInterface::class, '1')->shouldReturn(null);
    }
}
