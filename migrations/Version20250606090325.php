<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606090325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf(!$isSqlite);

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, media_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, content CLOB DEFAULT NULL --(DC2Type:json)
        , CONSTRAINT FK_23A0E66EA9FDD75 FOREIGN KEY (media_id) REFERENCES media_entity (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_23A0E66EA9FDD75 ON article (media_id)');
        $this->addSql('CREATE TABLE easy_admin__reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_DB1E0C65A76ED395 FOREIGN KEY (user_id) REFERENCES easy_admin__user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DB1E0C65A76ED395 ON easy_admin__reset_password_request (user_id)');
        $this->addSql('CREATE TABLE easy_admin__user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, enabled BOOLEAN NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AAFD4C04E7927C74 ON easy_admin__user (email)');
        $this->addSql('CREATE TABLE easy_block__block (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, block_key VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, settings CLOB NOT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F37A3E7E81B6293 ON easy_block__block (block_key)');
        $this->addSql('CREATE TABLE easy_blog__post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, state VARCHAR(100) NOT NULL, css CLOB DEFAULT NULL, js CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description CLOB DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap BOOLEAN DEFAULT NULL, seo_robots CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_F6E9233512469DE2 FOREIGN KEY (category_id) REFERENCES easy_post__category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6E92335989D9B62 ON easy_blog__post (slug)');
        $this->addSql('CREATE INDEX IDX_F6E9233512469DE2 ON easy_blog__post (category_id)');
        $this->addSql('CREATE TABLE easy_config__config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, config VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(255) NOT NULL, value CLOB DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E1ABDC0FD48A2F7C ON easy_config__config (config)');
        $this->addSql('CREATE TABLE easy_faq__category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, css CLOB DEFAULT NULL, js CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description CLOB DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap BOOLEAN DEFAULT NULL, seo_robots CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3A172E9989D9B62 ON easy_faq__category (slug)');
        $this->addSql('CREATE INDEX easy_faq_category_indexes ON easy_faq__category (created_at, status)');
        $this->addSql('CREATE TABLE easy_faq__entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, state VARCHAR(100) NOT NULL, answer CLOB NOT NULL, css CLOB DEFAULT NULL, js CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description CLOB DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap BOOLEAN DEFAULT NULL, seo_robots CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_D07E2DD212469DE2 FOREIGN KEY (category_id) REFERENCES easy_faq__category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D07E2DD2989D9B62 ON easy_faq__entry (slug)');
        $this->addSql('CREATE INDEX IDX_D07E2DD212469DE2 ON easy_faq__entry (category_id)');
        $this->addSql('CREATE INDEX easy_faq_indexes ON easy_faq__entry (state)');
        $this->addSql('CREATE TABLE easy_media__folder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, CONSTRAINT FK_1C446171727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_media__folder (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1C446171727ACA70 ON easy_media__folder (parent_id)');
        $this->addSql('CREATE TABLE easy_media__media (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, folder_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, mime VARCHAR(255) DEFAULT NULL, size INTEGER DEFAULT NULL, last_modified INTEGER DEFAULT NULL, metas CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_83D7599C162CB942 FOREIGN KEY (folder_id) REFERENCES easy_media__folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_83D7599C162CB942 ON easy_media__media (folder_id)');
        $this->addSql('CREATE TABLE easy_menu__menus (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status BOOLEAN NOT NULL, code VARCHAR(30) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE easy_menu__menus_items (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, menu_id INTEGER UNSIGNED DEFAULT NULL, parent_id INTEGER UNSIGNED DEFAULT NULL, state VARCHAR(100) NOT NULL, lft INTEGER NOT NULL, lvl INTEGER NOT NULL, rgt INTEGER NOT NULL, root INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, class_attribute VARCHAR(255) DEFAULT NULL, position SMALLINT UNSIGNED DEFAULT NULL, target BOOLEAN DEFAULT 0, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, CONSTRAINT FK_A119029DCCD7E912 FOREIGN KEY (menu_id) REFERENCES easy_menu__menus (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A119029D727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_menu__menus_items (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A119029DCCD7E912 ON easy_menu__menus_items (menu_id)');
        $this->addSql('CREATE INDEX IDX_A119029D727ACA70 ON easy_menu__menus_items (parent_id)');
        $this->addSql('CREATE TABLE easy_page__page (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, state VARCHAR(100) NOT NULL, content CLOB DEFAULT NULL --(DC2Type:json)
        , embed VARCHAR(255) DEFAULT NULL, image TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , "action" VARCHAR(255) DEFAULT NULL, template VARCHAR(255) DEFAULT NULL, css CLOB DEFAULT NULL, js CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, publish_date DATETIME DEFAULT NULL, unpublish_date DATETIME DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description CLOB DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap BOOLEAN DEFAULT NULL, seo_robots CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_2E074586727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_page__page (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E074586989D9B62 ON easy_page__page (slug)');
        $this->addSql('CREATE INDEX IDX_2E074586727ACA70 ON easy_page__page (parent_id)');
        $this->addSql('CREATE TABLE easy_post__category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, css CLOB DEFAULT NULL, js CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description CLOB DEFAULT NULL, seo_keywords VARCHAR(255) DEFAULT NULL, seo_cannonical VARCHAR(255) DEFAULT NULL, seo_cover TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , seo_key VARCHAR(255) DEFAULT NULL, seo_sitemap BOOLEAN DEFAULT NULL, seo_robots CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_30ECF456989D9B62 ON easy_post__category (slug)');
        $this->addSql('CREATE TABLE easy_redirect__not_found (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(500) NOT NULL, host VARCHAR(255) DEFAULT \'\' NOT NULL, full_url VARCHAR(500) NOT NULL, timestamp DATETIME NOT NULL, referer VARCHAR(500) DEFAULT NULL)');
        $this->addSql('CREATE TABLE easy_redirect__redirect (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, source VARCHAR(500) NOT NULL, host VARCHAR(255) DEFAULT \'\' NOT NULL, destination VARCHAR(500) NOT NULL, status VARCHAR(10) NOT NULL, count INTEGER NOT NULL, last_accessed DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE ext_log_entries (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "action" VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INTEGER NOT NULL, data CLOB DEFAULT NULL --(DC2Type:array)
        , username VARCHAR(191) DEFAULT NULL)');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('CREATE TABLE ext_translations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)');
        $this->addSql('CREATE TABLE media_entity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, file TEXT DEFAULT NULL --(DC2Type:easy_media_type)
        , text CLOB DEFAULT NULL, icon VARCHAR(50) DEFAULT NULL, data CLOB DEFAULT NULL --(DC2Type:json)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE easy_admin__reset_password_request');
        $this->addSql('DROP TABLE easy_admin__user');
        $this->addSql('DROP TABLE easy_block__block');
        $this->addSql('DROP TABLE easy_blog__post');
        $this->addSql('DROP TABLE easy_config__config');
        $this->addSql('DROP TABLE easy_faq__category');
        $this->addSql('DROP TABLE easy_faq__entry');
        $this->addSql('DROP TABLE easy_media__folder');
        $this->addSql('DROP TABLE easy_media__media');
        $this->addSql('DROP TABLE easy_menu__menus');
        $this->addSql('DROP TABLE easy_menu__menus_items');
        $this->addSql('DROP TABLE easy_page__page');
        $this->addSql('DROP TABLE easy_post__category');
        $this->addSql('DROP TABLE easy_redirect__not_found');
        $this->addSql('DROP TABLE easy_redirect__redirect');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE media_entity');
    }
}
