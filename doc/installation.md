## Installation
```bash
$ composer require bitbag/catalog-plugin
```

Add plugin dependencies to your `config/bundles.php` file:
```php
return [
    ...

    BitBag\SyliusCatalogPlugin\BitBagSyliusCatalogPlugin::class => ['all' => true]
];
```


Add config to your `config/packages/` directory for example in `config/packages/bitbag_sylius_catalog_plugin.yaml` file:

```yaml
# config/packages/bitbag_sylius_catalog_plugin.yaml

imports:
  - { resource: "@BitBagSyliusCatalogPlugin/Resources/config/config.yaml" }

bit_bag_sylius_catalog:
  driver: doctrine

```

If You are using Bitbag SyliusElasticsearchPlugin change driver to elasticsearch:

```yaml
# config/packages/bitbag_sylius_catalog_plugin.yaml

imports:
  - { resource: "@BitBagSyliusCatalogPlugin/Resources/config/config.yaml" }

bit_bag_sylius_catalog:
  driver: elasticsearch

```

Import routing for example by adding `config/routes/bitbag_sylius_catalog.yaml` file:

```yaml

# config/routes/bitbag_sylius_catalog.yaml

bitbag_sylius_catalog_plugin:
  resource: "@BitBagSyliusCatalogPlugin/Resources/config/routing.yaml"
```

To display catalogs in product details You need to override product details template, for example with template provided as a part of test application in
`vendor/bitbag/catalog-plugin/tests/Application/templates/bundles/SyliusShopBundle/Product/show.html.twig`

Finish the installation by updating the database schema and installing assets:
```
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
$ bin/console assets:install --symlink
$ bin/console sylius:theme:assets:install --symlink
```

## Testing & running the plugin
```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn build
$ bin/console assets:install public -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d public -e test
$ open http://localhost:8080
$ cd ../..
$ vendor/bin/behat
$ vendor/bin/phpspec run
```
