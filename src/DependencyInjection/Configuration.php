<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

final class Configuration implements ConfigurationInterface
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

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
                ->scalarNode('templates_dir')
                    ->defaultValue('catalog')
                    ->cannotBeEmpty()
                    ->validate()
                        ->always(function ($value) {
                            if (!is_string($value)) {
                                throw new InvalidConfigurationException('templates_dir must be string');
                            }

                            $fullDirPath = $this->projectDir.'/templates/'.$value;

                            if (!is_dir($fullDirPath)) {
                                throw new InvalidConfigurationException(sprintf('%s is not valid direction', $fullDirPath));
                            }
                            return $value;
                        })
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
