## Installation
```bash
$ composer require bitbag/catalog-plugin --no-scripts
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

Create directory for yours catalog template, by default `catalog`
```bash
$ mkdir -p templates/catalog
```
If you want to have product catalog templates in different directory, you can change `catalog` directory by changing `templates_dir`.
```yaml

# config/packages/bitbag_sylius_catalog_plugin.yaml

bit_bag_sylius_catalog:
    templates_dir: 'your_catalog_name'
```
Then your templates for catalog will be stored at `%kernel.project_dir%/Templates/your_catalog_name`.
Default template for product catalog is `@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig`



Finish the installation by updating the database schema and installing assets:
```
$ bin/console cache:clear
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
$ bin/console assets:install --symlink
$ bin/console sylius:theme:assets:install --symlink
```

### Parameters and Services which can be overridden
```yml
$ bin/console debug:container --parameters | grep bitbag_sylius_catalog_plugin
$ bin/console debug:container bitbag_sylius_catalog_plugin
```

## Testing & running the plugin
```bash
$ composer install
$ APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
$ cd ./tests/Application/
$ yarn install
$ yarn build
$ yarn encore dev
$ bin/console doctrine:database:create --env=test
$ bin/console doctrine:schema:create --env=test
$ bin/console assets:install --env=test
$ bin/console sylius:fixtures:load --env=test
$ symfony open:local
$ cd ../../
$ vendor/bin/behat
$ vendor/bin/phpspec run
$ vendor/bin/ecs check src
```
