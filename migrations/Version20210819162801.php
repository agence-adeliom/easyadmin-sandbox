<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210819162801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD seo_title VARCHAR(255) NOT NULL, ADD seo_description LONGTEXT DEFAULT NULL, ADD seo_keywords VARCHAR(255) DEFAULT NULL, ADD seo_cannonical VARCHAR(255) DEFAULT NULL, ADD seo_cover VARCHAR(255) DEFAULT NULL, ADD seo_key VARCHAR(255) DEFAULT NULL, ADD seo_sitemap TINYINT(1) NOT NULL, ADD seo_index TINYINT(1) NOT NULL, ADD seo_follow TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE page DROP seo_title, DROP seo_description, DROP seo_keywords, DROP seo_cannonical, DROP seo_cover, DROP seo_key, DROP seo_sitemap, DROP seo_index, DROP seo_follow');
    }
}
