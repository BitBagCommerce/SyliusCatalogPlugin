<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Controller\Shop;

use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowCatalogAction
{
    /** @var Environment */
    private $twig;

    /** @var RepositoryInterface */
    private $catalogRepository;

    /** @var ProductResolverInterface */
    private $productResolver;

    public function __construct(
        RepositoryInterface $catalogRepository,
        ProductsInsideCatalogResolverInterface $productResolver,
        Environment $twig
    ) {
        $this->twig = $twig;
        $this->catalogRepository = $catalogRepository;
        $this->productResolver = $productResolver;
    }

    public function __invoke(Request $request): Response
    {
        $catalog = $this->catalogRepository->findOneBy(['code' => $request->get('code')]);
        $products = $this->productResolver->findMatchingProducts($catalog);

        $template = $request->get('template');

        return new Response($this->twig->render($template, [
            'catalog' => $catalog,
            'products' => $products,
        ]));
    }
}
