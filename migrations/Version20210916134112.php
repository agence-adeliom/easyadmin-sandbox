<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916134112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_page__page DROP home_page');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_adjustment CHANGE details details LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE INDEX created_at_index ON sylius_product (created_at)');
        $this->addSql('CREATE INDEX enabled_index ON sylius_product (enabled)');
        $this->addSql('CREATE INDEX sylius_product_attribute_indexes ON sylius_product_attribute (storage_type, type)');
        $this->addSql('CREATE INDEX sylius_product_attribute_value_indexes ON sylius_product_attribute_value (locale_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_page__page ADD home_page TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sylius_adjustment CHANGE details details LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX created_at_index ON sylius_product');
        $this->addSql('DROP INDEX enabled_index ON sylius_product');
        $this->addSql('DROP INDEX sylius_product_attribute_indexes ON sylius_product_attribute');
        $this->addSql('DROP INDEX sylius_product_attribute_value_indexes ON sylius_product_attribute_value');
    }
}
