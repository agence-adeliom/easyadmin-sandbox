<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007153328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_admin__reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE easy_admin__reset_password_request ADD CONSTRAINT FK_DB1E0C65A76ED395 FOREIGN KEY (user_id) REFERENCES easy_admin__user (id)');
        $this->addSql('ALTER TABLE easy_block__block ADD block_key VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F37A3E7E81B6293 ON easy_block__block (block_key)');
        $this->addSql('ALTER TABLE easy_config__config CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry DROP content');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_admin__reset_password_request DROP FOREIGN KEY FK_DB1E0C65A76ED395');
        $this->addSql('ALTER TABLE easy_admin__reset_password_request CHANGE user_id user_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_9F37A3E7E81B6293 ON easy_block__block');
        $this->addSql('ALTER TABLE easy_block__block DROP block_key');
        $this->addSql('ALTER TABLE easy_config__config CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry ADD content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
