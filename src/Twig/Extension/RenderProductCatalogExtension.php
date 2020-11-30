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

use BitBag\SyliusCatalogPlugin\Checker\Rule\SortByCodeRuleChecker;
use BitBag\SyliusCatalogPlugin\Entity\Catalog;
use BitBag\SyliusCatalogPlugin\Entity\CatalogRule;
use BitBag\SyliusCatalogPlugin\Entity\RuleCheckerInterface;
use BitBag\SyliusCatalogPlugin\Repository\ProductRepository;
use BitBag\SyliusCatalogPlugin\Resolver\CatalogResourceResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;

final class RenderProductCatalogExtension extends AbstractExtension
{
    /** @var EngineInterface */
    private $engine;

    /** @var CatalogResourceResolverInterface */
    private $catalogResolver;

    /** @var EntityRepository */
    private $catalogRuleRepository;

    /** @var ServiceRegistry */
    private $serviceRegistry;

    /** @var ProductRepository */
    private $productRepository;

    public function __construct(EngineInterface $engine, ServiceRegistry $serviceRegistry, ProductRepository $productRepository,
                                CatalogResourceResolverInterface $catalogResolver, EntityRepository $catalogRuleRepository)
    {
        $this->productRepository = $productRepository;
        $this->catalogRuleRepository = $catalogRuleRepository;
        $this->engine = $engine;
        $this->catalogResolver = $catalogResolver;
        $this->serviceRegistry = $serviceRegistry;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('bitbag_render_product_catalog', [$this, 'renderProductCatalog'], ['is_safe' => ['html']])
        ];
    }

    public function renderProductCatalog(?string $code, ?string $template = null): string
    {
        /** @var Catalog $catalog */
        $catalog = $this->catalogResolver->findOrLog($code);

        $connectingRules = $catalog->getConnectingRules();

        /** @var CatalogRule $rules */
        $rules = $catalog->getRules();

        $qb = $this->productRepository->createQueryBuilder('p');
        foreach ($rules as $rule) {
            $type = $rule->getType();

            /** @var RuleCheckerInterface $ruleChecker */
            $ruleChecker = $this->serviceRegistry->get($type);

            $sortByCodeConfiguration = $rule->getConfiguration();

            $ruleChecker->modifyQueryBuilder($sortByCodeConfiguration, $qb, $connectingRules);
        }
        $products = $qb
            ->getQuery()->getResult();

        if ($products !== null && $catalog !== null) {
            $template = $template ?? '@BitBagSyliusCatalogPlugin/Catalog/showProducts.html.twig';

            return $this->engine->render($template, ['products' => $products, 'catalog' => $catalog]);
        }

        return ' ';
    }
}
