<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Twig\Extension;

use BitBag\SyliusCatalogPlugin\Repository\ProductCatalogRepository;
use BitBag\SyliusCatalogPlugin\Repository\ProductRepository;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;

final class RenderProductCatalogExtension extends AbstractExtension
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var EngineInterface
     */
    private $engine;

    public function __construct(ProductRepository $productRepository, EngineInterface $engine)
    {
        $this->productRepository = $productRepository;
        $this->engine = $engine;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('bitbag_render_product_catalog', [$this, 'renderProductCatalog'])
        ];
    }

    public function renderProductCatalog(): string
    {
        $products = $this->productRepository->FindProducts();

        if ($products !== null) {
            $template = $template ?? '@BitBagSyliusCatalogPlugin/Catalog/showProducts.html.twig';

            return $this->engine->render($template, ['products' => $products]);
        }

        return ' ';
    }
}
