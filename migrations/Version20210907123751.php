<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907123751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX faq_indexes ON entry (state)');
        $this->addSql('CREATE INDEX faq_category_indexes ON faq_categories (created_at, status)');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
        $this->addSql('CREATE INDEX enabled_index ON sylius_product (enabled)');
        $this->addSql('CREATE INDEX sylius_product_attribute_indexes ON sylius_product_attribute (storage_type, type)');
        $this->addSql('CREATE INDEX sylius_product_attribute_value_indexes ON sylius_product_attribute_value (locale_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX faq_indexes ON entry');
        $this->addSql('DROP INDEX faq_category_indexes ON faq_categories');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX enabled_index ON sylius_product');
        $this->addSql('DROP INDEX sylius_product_attribute_indexes ON sylius_product_attribute');
        $this->addSql('DROP INDEX sylius_product_attribute_value_indexes ON sylius_product_attribute_value');
    }
}
