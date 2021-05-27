<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @deprecated Since bitbag/catalog-plugin 1.0: Doctrine migrations existing in a bundle will be removed, move migrations to the project directory.
 */
final class Version20201209121018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bitbag_catalog_rule ADD target VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bitbag_catalog_rule DROP target');
    }
}
