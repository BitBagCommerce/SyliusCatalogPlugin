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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CatalogRuleCheckersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('bitbag_sylius_catalog_plugin.registry_catalog_rule_checker') || !$container
                ->has('bitbag_sylius_catalog_plugin.form_registry.catalog_rule_checker')) {
            return;
        }

        $catalogRuleCheckerRegistry = $container->getDefinition('bitbag_sylius_catalog_plugin.registry_catalog_rule_checker');
        $catalogRuleCheckerFormTypeRegistry = $container->getDefinition('bitbag_sylius_catalog_plugin.form_registry.catalog_rule_checker');

        $catalogRuleCheckerTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds('bitbag_sylius_catalog_plugin.catalog_rule_checker') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['type'], $attribute['label'], $attribute['form_type'])) {
                    throw new \InvalidArgumentException('Tagged rule checker `' . $id . '`needs to have `type`, `form_type` and `label` attributes');
                }

                $catalogRuleCheckerTypeToLabelMap[$attribute['type']] = $attribute['label'];
                $catalogRuleCheckerRegistry->addMethodCall('register', [$attribute['type'], new Reference($id)]);
                $catalogRuleCheckerFormTypeRegistry->addMethodCall('add', [$attribute['type'], 'default', $attribute['form_type']]);
            }
        }

        $container->setParameter('bitbag_sylius_catalog_plugin.catalog_rules', $catalogRuleCheckerTypeToLabelMap);
        $container->setParameter('bitbag_sylius_catalog_plugin.product_association_rules', $catalogRuleCheckerTypeToLabelMap);
    }
}
