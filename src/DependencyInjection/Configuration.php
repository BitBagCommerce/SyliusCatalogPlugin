<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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

                            $fullDirPath = $this->projectDir . '/templates/' . $value;

                            if (!is_dir($fullDirPath)) {
                                throw new InvalidConfigurationException(sprintf('%s is not valid directory', $fullDirPath));
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
