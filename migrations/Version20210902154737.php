<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902154737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE faq_categories_entries (entry_id INT UNSIGNED NOT NULL, category_id INT UNSIGNED NOT NULL, INDEX IDX_B1633D69BA364942 (entry_id), INDEX IDX_B1633D6912469DE2 (category_id), PRIMARY KEY(entry_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D69BA364942 FOREIGN KEY (entry_id) REFERENCES entry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faq_categories_entries ADD CONSTRAINT FK_B1633D6912469DE2 FOREIGN KEY (category_id) REFERENCES faq_categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE faq_categories_entries');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
