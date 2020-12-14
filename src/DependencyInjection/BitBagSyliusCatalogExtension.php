<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class BitBagSyliusCatalogExtension extends Extension
{
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
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('doctrine_migrations') || !$container->hasExtension('sylius_labs_doctrine_migrations_extra')) {
            return;
        }

        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => [
                'BitBag\SyliusCatalogPlugin\Migrations' => '@BitBagSyliusCatalogPlugin/Migrations',
            ],
        ]);

        $container->prependExtensionConfig('sylius_labs_doctrine_migrations_extra', [
            'migrations' => [
                'BitBag\SyliusCatalogPlugin\Migrations' => ['Sylius\Bundle\CoreBundle\Migrations'],
            ],
        ]);
    }
}
