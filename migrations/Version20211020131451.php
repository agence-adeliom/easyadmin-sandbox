<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211020131451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_page__page ADD embed VARCHAR(255) DEFAULT NULL, CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_cover seo_cover VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_cover seo_cover VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE seo_cover seo_cover VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_page__page DROP embed, CHANGE seo_cover seo_cover VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_cover seo_cover VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
