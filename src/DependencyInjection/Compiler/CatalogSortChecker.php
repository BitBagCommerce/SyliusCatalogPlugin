<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DependencyInjection\Compiler;

use BitBag\SyliusCatalogPlugin\Checker\Sort\Doctrine\SortInterface as DoctrineSortInterface;
use BitBag\SyliusCatalogPlugin\Checker\Sort\Elasticsearch\SortInterface as ElasticsearchSortInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CatalogSortChecker implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $drivers = [
            'doctrine' => DoctrineSortInterface::class,
            'elasticsearch' => ElasticsearchSortInterface::class,
        ];

        foreach ($drivers as $driver => $sortInterface) {
            $this->addDriverSortsToRegistries($driver, $container, $sortInterface);
        }
    }

    private function serviceImplementsInterface(ContainerBuilder $container, string $id, string $sortInterface): bool
    {
        return isset(class_implements($container->getDefinition($id)->getClass())[$sortInterface]);
    }

    private function addDriverSortsToRegistries(string $driver, ContainerBuilder $container, string $sortInterface): void
    {
        $driverSortRegistry = sprintf('bitbag_sylius_catalog_plugin.registry_catalog_sort_checker.%s', $driver);

        if (!$container->has($driverSortRegistry)) {
            return;
        }

        $catalogSortCheckerRegistry = $container->getDefinition($driverSortRegistry);

        $catalogSortCheckerTypeToLabelMap = [];

        foreach ($container->findTaggedServiceIds('bitbag_sylius_catalog_plugin.catalog_sort_checker') as $id => $attributes) {
            if ($this->serviceImplementsInterface($container, $id, $sortInterface)) {
                foreach ($attributes as $attribute) {
                    if (!isset($attribute['type'], $attribute['label'])) {
                        throw new \InvalidArgumentException('Tagged sort checker `' . $id . '`needs to have `type` and `label` attributes');
                    }

                    $catalogSortCheckerTypeToLabelMap[$attribute['type']] = $attribute['label'];
                    $catalogSortCheckerRegistry->addMethodCall('register', [$attribute['type'], new Reference($id)]);
                }
            }
        }

        $container->setParameter(sprintf('bitbag_sylius_catalog_plugin.catalog_sorts.%s', $driver), $catalogSortCheckerTypeToLabelMap);
    }
}
