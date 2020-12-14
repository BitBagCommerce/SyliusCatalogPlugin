<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DependencyInjection\Compiler;

use BitBag\SyliusCatalogPlugin\Checker\Rule\Doctrine\RuleInterface as DoctrineRuleInterface;
use BitBag\SyliusCatalogPlugin\Checker\Rule\Elasticsearch\RuleInterface as ElasticRuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CatalogRuleCheckersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $drivers = [
            'doctrine' => DoctrineRuleInterface::class,
            'elasticsearch' => ElasticRuleInterface::class,
        ];

        foreach ($drivers as $driver => $ruleInterface) {
            $this->addDriverRulesToRegistries($driver, $container, $ruleInterface);
        }
    }

    private function serviceImplementsInterface(ContainerBuilder $container, string $id, string $ruleInterface): bool
    {
        return isset(class_implements($container->getDefinition($id)->getClass())[$ruleInterface]);
    }

    private function addDriverRulesToRegistries(string $driver, ContainerBuilder $container, string $ruleInterface): void
    {
        $driverRuleRegistry = sprintf('bitbag_sylius_catalog_plugin.registry_catalog_rule_checker.%s', $driver);
        $driverFormRegistry = sprintf('bitbag_sylius_catalog_plugin.form_registry.catalog_rule_checker.%s', $driver);
        if (!$container->has($driverRuleRegistry) || !$container->has($driverFormRegistry)) {
            return;
        }

        $catalogRuleCheckerRegistry = $container->getDefinition($driverRuleRegistry);
        $catalogRuleCheckerFormTypeRegistry = $container->getDefinition($driverFormRegistry);

        $catalogRuleCheckerTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds('bitbag_sylius_catalog_plugin.catalog_rule_checker') as $id => $attributes) {
            if ($this->serviceImplementsInterface($container, $id, $ruleInterface)) {
                foreach ($attributes as $attribute) {
                    if (!isset($attribute['type'], $attribute['label'], $attribute['form_type'])) {
                        throw new \InvalidArgumentException('Tagged rule checker `' . $id . '`needs to have `type`, `form_type` and `label` attributes');
                    }

                    $catalogRuleCheckerTypeToLabelMap[$attribute['type']] = $attribute['label'];
                    $catalogRuleCheckerRegistry->addMethodCall('register', [$attribute['type'], new Reference($id)]);
                    $catalogRuleCheckerFormTypeRegistry->addMethodCall('add', [$attribute['type'], 'default', $attribute['form_type']]);
                }
            }
        }

        $container->setParameter(sprintf('bitbag_sylius_catalog_plugin.catalog_rules.%s', $driver), $catalogRuleCheckerTypeToLabelMap);
        $container->setParameter(sprintf('bitbag_sylius_catalog_plugin.product_association_rules.%s', $driver), $catalogRuleCheckerTypeToLabelMap);
    }
}
