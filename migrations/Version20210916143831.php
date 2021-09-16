<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916143831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_23A0E66EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_admin__reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_id INT NOT NULL, INDEX IDX_DB1E0C65A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_admin__user (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AAFD4C04E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_block__block (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, settings LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_blog__post (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED DEFAULT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, state VARCHAR(100) NOT NULL, css LONGTEXT DEFAULT NULL, js LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, seo_title VARCHAR(255) NOT NULL, seo_description LONGTEXT DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover VARCHAR(255) DEFAULT NULL, seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap TINYINT(1) NOT NULL, seo_robots LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_F6E92335989D9B62 (slug), INDEX IDX_F6E9233512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_config__config (id INT AUTO_INCREMENT NOT NULL, config VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_E1ABDC0FD48A2F7C (config), UNIQUE INDEX UNIQ_E1ABDC0F8CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_faq__category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, css LONGTEXT DEFAULT NULL, js LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, seo_title VARCHAR(255) NOT NULL, seo_description LONGTEXT DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover VARCHAR(255) DEFAULT NULL, seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap TINYINT(1) NOT NULL, seo_robots LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_C3A172E9989D9B62 (slug), INDEX easy_faq_category_indexes (created_at, status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_faq__entry (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, state VARCHAR(100) NOT NULL, question VARCHAR(255) NOT NULL, answer LONGTEXT NOT NULL, content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', css LONGTEXT DEFAULT NULL, js LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, seo_title VARCHAR(255) NOT NULL, seo_description LONGTEXT DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover VARCHAR(255) DEFAULT NULL, seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap TINYINT(1) NOT NULL, seo_robots LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_D07E2DD2989D9B62 (slug), INDEX easy_faq_indexes (state), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq_categories_entries (entry_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL, INDEX IDX_B1633D69BA364942 (entry_id), INDEX IDX_B1633D6912469DE2 (category_id), PRIMARY KEY(entry_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_media__lock (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_media__metas (id INT AUTO_INCREMENT NOT NULL, path LONGTEXT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_menu__menus (id INT UNSIGNED AUTO_INCREMENT NOT NULL, status TINYINT(1) NOT NULL, code VARCHAR(30) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_menu__menus_items (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, menu_id INT UNSIGNED DEFAULT NULL, state VARCHAR(100) NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, class_attribute VARCHAR(255) DEFAULT NULL, position SMALLINT UNSIGNED DEFAULT NULL, target TINYINT(1) DEFAULT \'0\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, root INT DEFAULT NULL, INDEX IDX_A119029D727ACA70 (parent_id), INDEX IDX_A119029DCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_page__page (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, state VARCHAR(100) NOT NULL, content LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', action VARCHAR(255) DEFAULT NULL, template VARCHAR(255) DEFAULT NULL, css LONGTEXT DEFAULT NULL, js LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, seo_title VARCHAR(255) NOT NULL, seo_description LONGTEXT DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover VARCHAR(255) DEFAULT NULL, seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap TINYINT(1) NOT NULL, seo_robots LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_2E074586989D9B62 (slug), INDEX IDX_2E074586727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_post__category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, status TINYINT(1) NOT NULL, css LONGTEXT DEFAULT NULL, js LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, seo_title VARCHAR(255) NOT NULL, seo_description LONGTEXT DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover VARCHAR(255) DEFAULT NULL, seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap TINYINT(1) NOT NULL, seo_robots LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_30ECF456989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_redirect__not_found (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(500) NOT NULL, full_url VARCHAR(500) NOT NULL, timestamp DATETIME NOT NULL, referer VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_redirect__redirect (id INT AUTO_INCREMENT NOT NULL, source VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, status VARCHAR(10) NOT NULL, count INT NOT NULL, last_accessed DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1ADAE4A05F8A7F73 (source), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(191) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE media_entity (id INT AUTO_INCREMENT NOT NULL, file TEXT DEFAULT NULL, text LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66EA9FDD75 FOREIGN KEY (media_id) REFERENCES media_entity (id)');
        $this->addSql('ALTER TABLE easy_blog__post ADD CONSTRAINT FK_F6E9233512469DE2 FOREIGN KEY (category_id) REFERENCES easy_post__category (id)');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D69BA364942 FOREIGN KEY (entry_id) REFERENCES easy_faq__entry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D6912469DE2 FOREIGN KEY (category_id) REFERENCES easy_faq__category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE easy_menu__menus_items ADD CONSTRAINT FK_A119029D727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_menu__menus_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE easy_menu__menus_items ADD CONSTRAINT FK_A119029DCCD7E912 FOREIGN KEY (menu_id) REFERENCES easy_menu__menus (id)');
        $this->addSql('ALTER TABLE easy_page__page ADD CONSTRAINT FK_2E074586727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_page__page (id)');
        $this->addSql('ALTER TABLE sylius_adjustment CHANGE details details LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE INDEX created_at_index ON sylius_product (created_at)');
        $this->addSql('CREATE INDEX enabled_index ON sylius_product (enabled)');
        $this->addSql('CREATE INDEX sylius_product_attribute_indexes ON sylius_product_attribute (storage_type, type)');
        $this->addSql('CREATE INDEX sylius_product_attribute_value_indexes ON sylius_product_attribute_value (locale_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faq_categories_entries DROP FOREIGN KEY FK_B1633D6912469DE2');
        $this->addSql('ALTER TABLE faq_categories_entries DROP FOREIGN KEY FK_B1633D69BA364942');
        $this->addSql('ALTER TABLE easy_menu__menus_items DROP FOREIGN KEY FK_A119029DCCD7E912');
        $this->addSql('ALTER TABLE easy_menu__menus_items DROP FOREIGN KEY FK_A119029D727ACA70');
        $this->addSql('ALTER TABLE easy_page__page DROP FOREIGN KEY FK_2E074586727ACA70');
        $this->addSql('ALTER TABLE easy_blog__post DROP FOREIGN KEY FK_F6E9233512469DE2');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66EA9FDD75');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE easy_admin__reset_password_request');
        $this->addSql('DROP TABLE easy_admin__user');
        $this->addSql('DROP TABLE easy_block__block');
        $this->addSql('DROP TABLE easy_blog__post');
        $this->addSql('DROP TABLE easy_config__config');
        $this->addSql('DROP TABLE easy_faq__category');
        $this->addSql('DROP TABLE easy_faq__entry');
        $this->addSql('DROP TABLE faq_categories_entries');
        $this->addSql('DROP TABLE easy_media__lock');
        $this->addSql('DROP TABLE easy_media__metas');
        $this->addSql('DROP TABLE easy_menu__menus');
        $this->addSql('DROP TABLE easy_menu__menus_items');
        $this->addSql('DROP TABLE easy_page__page');
        $this->addSql('DROP TABLE easy_post__category');
        $this->addSql('DROP TABLE easy_redirect__not_found');
        $this->addSql('DROP TABLE easy_redirect__redirect');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE media_entity');
        $this->addSql('ALTER TABLE sylius_adjustment CHANGE details details LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX created_at_index ON sylius_product');
        $this->addSql('DROP INDEX enabled_index ON sylius_product');
        $this->addSql('DROP INDEX sylius_product_attribute_indexes ON sylius_product_attribute');
        $this->addSql('DROP INDEX sylius_product_attribute_value_indexes ON sylius_product_attribute_value');
    }
}
