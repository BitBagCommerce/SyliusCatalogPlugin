<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @deprecated Since bitbag/catalog-plugin 1.0: Doctrine migrations existing in a bundle will be removed, move migrations to the project directory.
 */
final class Version20210219121134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bitbag_catalog_catalog ADD template VARCHAR(255) NOT NULL, ADD sorting_type VARCHAR(255) NOT NULL, ADD display_products INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bitbag_catalog_catalog DROP template, DROP sorting_type, DROP display_products');
    }
}
