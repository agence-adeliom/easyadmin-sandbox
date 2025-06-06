<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208141323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf($isSqlite);
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE content content JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_admin__user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_block__block CHANGE settings settings JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_media__media CHANGE metas metas JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_page__page ADD image TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\', CHANGE content content JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE media_entity CHANGE data data JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf($isSqlite);
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE data data JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_admin__user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_page__page DROP image, CHANGE content content JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE article CHANGE content content JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_block__block CHANGE settings settings JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_media__media CHANGE metas metas JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_robots seo_robots JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
