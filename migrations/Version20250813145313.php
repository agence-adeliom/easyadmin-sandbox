<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250813145313 extends AbstractMigration
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
        $this->addSql('ALTER TABLE easy_blog__post ADD content JSON DEFAULT NULL COMMENT \'(DC2Type:easy_editor_type)\', CHANGE seo_title seo_title VARCHAR(255) DEFAULT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_title seo_title VARCHAR(255) DEFAULT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE seo_title seo_title VARCHAR(255) DEFAULT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_page__page CHANGE seo_title seo_title VARCHAR(255) DEFAULT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_title seo_title VARCHAR(255) DEFAULT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf($isSqlite);
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_title seo_title VARCHAR(255) NOT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE seo_title seo_title VARCHAR(255) NOT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE easy_page__page CHANGE seo_title seo_title VARCHAR(255) NOT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE easy_blog__post DROP content, CHANGE seo_title seo_title VARCHAR(255) NOT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_title seo_title VARCHAR(255) NOT NULL, CHANGE seo_sitemap seo_sitemap TINYINT(1) NOT NULL');
    }
}
