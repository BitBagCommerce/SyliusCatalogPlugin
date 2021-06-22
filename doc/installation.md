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
$ symfony console doctrine:migrations:diff
$ symfony console doctrine:migrations:migrate
$ symfony console assets:install --symlink
$ symfony console sylius:theme:assets:install --symlink
```

### Parameters and Services which can be overridden
```yml
$ symfony console debug:container --parameters | grep bitbag_sylius_catalog_plugin
$ symfony console debug:container bitbag_sylius_catalog_plugin
```

## Testing & running the plugin
```bash
$ symfony composer install
$ APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
$ cd ./tests/Application/
$ symfony run yarn install
$ symfony run yarn build
$ symfony console doctrine:database:create --env=test
$ symfony console doctrine:schema:create --env=test
$ symfony console assets:install --env=test
$ symfony console sylius:fixtures:load --env=test
$ symfony open:local
$ cd ../../
$ vendor/bin/behat
$ vendor/bin/phpspec run
$ vendor/bin/ecs check src
```
