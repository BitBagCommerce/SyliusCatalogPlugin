# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
---
FRONTEND
- [Templates](#templates)
---
ADDITIONAL
- [Additional configuration](#additional-configuration)
- [Tests](#tests)
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.0          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 14.x        |

## Composer:
```bash
composer require bitbag/catalog-plugin --no-scripts
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusCatalogPlugin\BitBagSyliusCatalogPlugin::class => ['all' => true]
];
```

Import required config in `config/packages/bitbag_sylius_catalog_plugin.yaml` file:

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

Import routing in your `config/routes/bitbag_sylius_catalog_plugin.yaml` file:
```yaml
# config/routes/bitbag_sylius_catalog_plugin.yaml

bitbag_sylius_catalog_plugin:
    resource: "@BitBagSyliusCatalogPlugin/Resources/config/routing.yaml"
```

### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.


## Templates
To display catalogs in product details You need to override product details template,
for example with template provided as a part of test application. 

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
```
vendor/bitbag/catalog-plugin/tests/Application/templates/bundles/SyliusShopBundle/Product/show.html.twig
```

### Plugin provides 2 new twig functions which can be used inside templates:

- for rendering catalogs by their code:

Renders a catalog in place of the included code: `Rules - which products should be included inside catalog`
```php
{{ bitbag_render_product_catalog("test_catalog") }}
```
- for rendering all catalogs active for given product:

Renders catalogues for the products that have been selected in the rule : `Rules - in which products catalog should be shown`
```php
{{ bitbag_render_product_catalogs(product) }}
```

### Catalog template customization
Create directory for yours catalog template, by default catalog
```bash
mkdir -p templates/catalog
```

Default template for product catalog is `@BitBagSyliusCatalogPlugin/Catalog/Templates/showProducts.html.twig`.
You can copy it into `%kernel.project_dir%/templates/` by the command below and edit it.

```bash
cp vendor/bitbag/catalog-plugin/src/Resources/views/Catalog/Templates/showProducts.html.twig templates/catalog/custom_template.html.twig
```
The selection of templates takes place in the admin panel separately for each catalogue.

### Catalog templates directory
If you want to have product catalog templates in different directory,
you can change catalog directory by changing templates_dir.

```yaml
# config/packages/bitbag_sylius_catalog_plugin.yaml

bit_bag_sylius_catalog:
    templates_dir: 'your_catalog_name'
```
Then your templates for catalog will be stored at `%kernel.project_dir%/templates/your_catalog_name`.

## Additional configuration
### Parameters and Services which can be overridden
```bash
bin/console debug:container --parameters | grep bitbag_sylius_catalog_plugin
bin/console debug:container bitbag_sylius_catalog_plugin
```

## Tests
To run the tests, execute the commands:
```bash
 composer install
 APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
 cd ./tests/Application/
 yarn install
 yarn build
 yarn encore dev
 bin/console doctrine:database:create --env=test
 bin/console doctrine:schema:create --env=test
 bin/console assets:install --env=test
 bin/console sylius:fixtures:load --env=test
 symfony open:local
 cd ../../
 vendor/bin/behat
 vendor/bin/phpspec run
 vendor/bin/ecs check src
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
