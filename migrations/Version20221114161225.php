<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114161225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faq_categories_entries DROP FOREIGN KEY FK_B1633D6912469DE2');
        $this->addSql('ALTER TABLE faq_categories_entries DROP FOREIGN KEY FK_B1633D69BA364942');
        $this->addSql('DROP TABLE faq_categories_entries');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('DROP INDEX UNIQ_E1ABDC0F8CDE5729 ON easy_config__config');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_faq__entry ADD category_id INT UNSIGNED DEFAULT NULL, DROP question, CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_faq__entry ADD CONSTRAINT FK_D07E2DD212469DE2 FOREIGN KEY (category_id) REFERENCES easy_faq__category (id)');
        $this->addSql('CREATE INDEX IDX_D07E2DD212469DE2 ON easy_faq__entry (category_id)');
        $this->addSql('ALTER TABLE easy_media__folder DROP FOREIGN KEY FK_1C446171727ACA70');
        $this->addSql('ALTER TABLE easy_media__folder ADD CONSTRAINT FK_1C446171727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_media__folder (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE easy_page__page CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
        $this->addSql('ALTER TABLE easy_redirect__not_found CHANGE referer referer VARCHAR(500) DEFAULT NULL');
        $this->addSql('CREATE INDEX general_translations_lookup_idx ON ext_translations (object_class, foreign_key)');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL COMMENT \'(DC2Type:easy_media_type)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE faq_categories_entries (entry_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL, INDEX IDX_B1633D6912469DE2 (category_id), INDEX IDX_B1633D69BA364942 (entry_id), PRIMARY KEY(entry_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D6912469DE2 FOREIGN KEY (category_id) REFERENCES easy_faq__category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D69BA364942 FOREIGN KEY (entry_id) REFERENCES easy_faq__entry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E1ABDC0F8CDE5729 ON easy_config__config (type)');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry DROP FOREIGN KEY FK_D07E2DD212469DE2');
        $this->addSql('DROP INDEX IDX_D07E2DD212469DE2 ON easy_faq__entry');
        $this->addSql('ALTER TABLE easy_faq__entry ADD question VARCHAR(255) NOT NULL, DROP category_id, CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_media__folder DROP FOREIGN KEY FK_1C446171727ACA70');
        $this->addSql('ALTER TABLE easy_media__folder ADD CONSTRAINT FK_1C446171727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_media__folder (id)');
        $this->addSql('ALTER TABLE easy_page__page CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_post__category CHANGE seo_cover seo_cover TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_redirect__not_found CHANGE referer referer VARCHAR(1000) DEFAULT NULL');
        $this->addSql('DROP INDEX general_translations_lookup_idx ON ext_translations');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
    }
}
