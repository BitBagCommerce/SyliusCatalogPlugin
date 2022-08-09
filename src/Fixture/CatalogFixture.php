<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
        /* @phpstan-ignore-next-line We ignored this line due to typing issues in NodeDefinition related classes. */
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
