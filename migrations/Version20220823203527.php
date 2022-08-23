<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823203527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_catalog_promotion (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, priority INT DEFAULT 0 NOT NULL, exclusive TINYINT(1) DEFAULT \'0\' NOT NULL, state VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1055865077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_channels (catalog_promotion_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_48E9AE7622E2CB5A (catalog_promotion_id), INDEX IDX_48E9AE7672F5A1AA (channel_id), PRIMARY KEY(catalog_promotion_id, channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_action (id INT AUTO_INCREMENT NOT NULL, catalog_promotion_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_F529624722E2CB5A (catalog_promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_scope (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_584AA86A139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_BA065D3C2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_catalog_promotion_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_channel_pricing_catalog_promotions (channel_pricing_id INT NOT NULL, catalog_promotion_id INT NOT NULL, INDEX IDX_9F52FF513EADFFE5 (channel_pricing_id), INDEX IDX_9F52FF5122E2CB5A (catalog_promotion_id), PRIMARY KEY(channel_pricing_id, catalog_promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_channels ADD CONSTRAINT FK_48E9AE7622E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_channels ADD CONSTRAINT FK_48E9AE7672F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_action ADD CONSTRAINT FK_F529624722E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id)');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_scope ADD CONSTRAINT FK_584AA86A139DF194 FOREIGN KEY (promotion_id) REFERENCES sylius_catalog_promotion (id)');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_translation ADD CONSTRAINT FK_BA065D3C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_catalog_promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_pricing_catalog_promotions ADD CONSTRAINT FK_9F52FF513EADFFE5 FOREIGN KEY (channel_pricing_id) REFERENCES sylius_channel_pricing (id)');
        $this->addSql('ALTER TABLE sylius_channel_pricing_catalog_promotions ADD CONSTRAINT FK_9F52FF5122E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id)');
        $this->addSql('DROP TABLE faq_categories_entries');
        $this->addSql('ALTER TABLE easy_admin__reset_password_request CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('DROP INDEX UNIQ_E1ABDC0F8CDE5729 ON easy_config__config');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_faq__entry ADD category_id INT UNSIGNED DEFAULT NULL, DROP question, CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_faq__entry ADD CONSTRAINT FK_D07E2DD212469DE2 FOREIGN KEY (category_id) REFERENCES easy_faq__category (id)');
        $this->addSql('CREATE INDEX IDX_D07E2DD212469DE2 ON easy_faq__entry (category_id)');
        $this->addSql('ALTER TABLE easy_media__folder DROP FOREIGN KEY FK_1C446171727ACA70');
        $this->addSql('ALTER TABLE easy_media__folder CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE parent_id parent_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_media__folder ADD CONSTRAINT FK_1C446171727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_media__folder (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE easy_media__media CHANGE folder_id folder_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_page__page CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD minimum_price INT DEFAULT 0');
        $this->addSql('CREATE INDEX created_at_index ON sylius_customer (created_at)');
        $this->addSql('ALTER TABLE sylius_gateway_config CHANGE config config LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE sylius_order_item ADD original_unit_price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_payment CHANGE details details LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE json_value json_value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE sylius_promotion ADD applies_to_discounted TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_catalog_promotion_channels DROP FOREIGN KEY FK_48E9AE7622E2CB5A');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_action DROP FOREIGN KEY FK_F529624722E2CB5A');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_scope DROP FOREIGN KEY FK_584AA86A139DF194');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_translation DROP FOREIGN KEY FK_BA065D3C2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_channel_pricing_catalog_promotions DROP FOREIGN KEY FK_9F52FF5122E2CB5A');
        $this->addSql('CREATE TABLE faq_categories_entries (entry_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL, INDEX IDX_B1633D69BA364942 (entry_id), INDEX IDX_B1633D6912469DE2 (category_id), PRIMARY KEY(entry_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D6912469DE2 FOREIGN KEY (category_id) REFERENCES easy_faq__category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D69BA364942 FOREIGN KEY (entry_id) REFERENCES easy_faq__entry (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sylius_catalog_promotion');
        $this->addSql('DROP TABLE sylius_catalog_promotion_channels');
        $this->addSql('DROP TABLE sylius_catalog_promotion_action');
        $this->addSql('DROP TABLE sylius_catalog_promotion_scope');
        $this->addSql('DROP TABLE sylius_catalog_promotion_translation');
        $this->addSql('DROP TABLE sylius_channel_pricing_catalog_promotions');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE easy_admin__reset_password_request CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_cover seo_cover TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E1ABDC0F8CDE5729 ON easy_config__config (type)');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_cover seo_cover TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_faq__entry DROP FOREIGN KEY FK_D07E2DD212469DE2');
        $this->addSql('DROP INDEX IDX_D07E2DD212469DE2 ON easy_faq__entry');
        $this->addSql('ALTER TABLE easy_faq__entry ADD question VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP category_id, CHANGE seo_cover seo_cover TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_media__folder DROP FOREIGN KEY FK_1C446171727ACA70');
        $this->addSql('ALTER TABLE easy_media__folder CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_media__folder ADD CONSTRAINT FK_1C446171727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_media__folder (id)');
        $this->addSql('ALTER TABLE easy_media__media CHANGE folder_id folder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_page__page CHANGE seo_cover seo_cover TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_cover seo_cover TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX general_translations_lookup_idx ON ext_translations');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sylius_channel_pricing DROP minimum_price');
        $this->addSql('DROP INDEX created_at_index ON sylius_customer');
        $this->addSql('ALTER TABLE sylius_gateway_config CHANGE config config LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE sylius_order_item DROP original_unit_price');
        $this->addSql('ALTER TABLE sylius_payment CHANGE details details LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE json_value json_value LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE sylius_promotion DROP applies_to_discounted');
    }
}
