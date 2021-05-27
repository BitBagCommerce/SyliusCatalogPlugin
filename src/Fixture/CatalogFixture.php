<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Fixture;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\RuleInterface;
use BitBag\SyliusCatalogPlugin\Fixture\Factory\CatalogFixtureFactory;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class CatalogFixture extends AbstractFixture implements FixtureInterface
{
    private CatalogFixtureFactory $catalogFixtureFactory;

    public function __construct(CatalogFixtureFactory $catalogFixtureFactory)
    {
        $this->catalogFixtureFactory = $catalogFixtureFactory;
    }

    public function load(array $options): void
    {
        $this->catalogFixtureFactory->load($options['custom']);
    }

    public function getName(): string
    {
        return 'catalog';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('custom')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('starts_at')->defaultNull()->end()
                            ->scalarNode('ends_at')->defaultNull()->end()
                            ->scalarNode('template')->defaultValue('@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig')->end()
                            ->arrayNode('translations')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->defaultNull()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('rules')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('type')->defaultNull()->end()
                                        ->variableNode('config')->defaultNull()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->enumNode('rules_operator')->values([RuleInterface::AND, RuleInterface::OR])->end()
                            ->arrayNode('associated_products_rules')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('type')->defaultNull()->end()
                                        ->variableNode('config')->defaultNull()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->enumNode('associated_products_rules_operator')->values([RuleInterface::AND, RuleInterface::OR])->end()
                            ->enumNode('sorting_type')->values(['newest', 'oldest', 'most_wishlist', 'cheapest', 'most_expensive', 'bestsellers'])->end()
                            ->integerNode('display_products')
                                ->min(1)
                                ->max(12)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
