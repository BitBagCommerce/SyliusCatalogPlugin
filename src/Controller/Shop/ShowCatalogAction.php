<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Controller\Shop;

use BitBag\SyliusCatalogPlugin\Entity\CatalogInterface;
use BitBag\SyliusCatalogPlugin\Resolver\ProductsInsideCatalogResolverInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

final class ShowCatalogAction
{
    private Environment $twig;

    private RepositoryInterface $catalogRepository;

    private ProductsInsideCatalogResolverInterface $productResolver;

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
        /** @var CatalogInterface|null $catalog */
        $catalog = $this->catalogRepository->findOneBy(['code' => $request->get('code')]);

        if (null === $catalog) {
            throw new NotFoundHttpException();
        }

        $products = $this->productResolver->findMatchingProducts($catalog);

        $template = $request->get('template');

        return new Response($this->twig->render($template, [
            'catalog' => $catalog,
            'products' => $products,
        ]));
    }
}
