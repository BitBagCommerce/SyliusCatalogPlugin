<?php

declare(strict_types=1);

namespace BitBag\SyliusCatalogPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207113934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bitbag_catalog_catalog_products (catalog_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2ED1B2B1CC3C66FC (catalog_id), INDEX IDX_2ED1B2B14584665A (product_id), PRIMARY KEY(catalog_id, product_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bitbag_catalog_catalog_products ADD CONSTRAINT FK_2ED1B2B1CC3C66FC FOREIGN KEY (catalog_id) REFERENCES bitbag_catalog_catalog (id)');
        $this->addSql('ALTER TABLE bitbag_catalog_catalog_products ADD CONSTRAINT FK_2ED1B2B14584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bitbag_catalog_catalog_products');
    }
}
