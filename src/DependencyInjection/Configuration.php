<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bitbag_sylius_catalog_plugin');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->enumNode('driver')
            ->values(['doctrine', 'elasticsearch'])
            ->defaultValue('doctrine')
            ->end()
        ;

        return $treeBuilder;
    }
}
