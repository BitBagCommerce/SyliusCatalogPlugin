<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class BitBagSyliusCatalogExtension extends Extension
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('BitBagSyliusElasticsearchPlugin', $bundles)) {
            $loader->load('services/integrations/elasticsearch.xml');
        }

        $container->setAlias('bitbag_sylius_catalog_plugin.registry_catalog_rule_checker', sprintf('bitbag_sylius_catalog_plugin.registry_catalog_rule_checker.%s', $config['driver']));
        $container->setAlias('bitbag_sylius_catalog_plugin.form_registry.catalog_rule_checker', sprintf('bitbag_sylius_catalog_plugin.form_registry.catalog_rule_checker.%s', $config['driver']));

        $container->setAlias('bitbag_sylius_catalog_plugin.registry_catalog_sort_checker', sprintf('bitbag_sylius_catalog_plugin.registry_catalog_sort_checker.%s', $config['driver']));

        $container->setDefinition(
            'bitbag_sylius_catalog_plugin.form.type.catalog_rule.choice',
            $container->getDefinition('bitbag_sylius_catalog_plugin.form.type.catalog_rule.choice')
                ->setArgument(0, sprintf('%%bitbag_sylius_catalog_plugin.catalog_rules.%s%%', $config['driver']))
        );

        $container->setDefinition(
            'bitbag_sylius_catalog_plugin.form.type.channel_pricing',
            $container->getDefinition('bitbag_sylius_catalog_plugin.form.type.channel_pricing')
                ->setArgument(0, sprintf('%%bitbag_sylius_catalog_plugin.catalog_rules.%s%%', $config['driver']))
        );

        $container->setDefinition(
            'bitbag_sylius_catalog_plugin.form.type.product_association_rule.choice',
            $container->getDefinition('bitbag_sylius_catalog_plugin.form.type.product_association_rule.choice')
                ->setArgument(0, sprintf('%%bitbag_sylius_catalog_plugin.product_association_rules.%s%%', $config['driver']))
        );

        $container->setDefinition(
            'bitbag_sylius_catalog_plugin.form.type.catalog',
            $container->getDefinition('bitbag_sylius_catalog_plugin.form.type.catalog')
                ->setArgument(1, sprintf('%%bitbag_sylius_catalog_plugin.catalog_sorts.%s%%', $config['driver']))
        );
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    public function prepend(ContainerBuilder $container): void
    {
        trigger_deprecation('bitbag/catalog-plugin', '1.0', 'Doctrine migrations existing in a bundle will be removed, move migrations to the project directory.');
        $this->prependDoctrineMigrations($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'BitBag\SyliusCatalogPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@BitBagSyliusCatalogPlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }
}
