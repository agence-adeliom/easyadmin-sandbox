<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210818144838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD tree_root INT UNSIGNED DEFAULT NULL, ADD parent_id INT UNSIGNED DEFAULT NULL, ADD lft INT NOT NULL, ADD lvl INT NOT NULL, ADD rgt INT NOT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620A977936C FOREIGN KEY (tree_root) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_140AB620A977936C ON page (tree_root)');
        $this->addSql('CREATE INDEX IDX_140AB620727ACA70 ON page (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620A977936C');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620727ACA70');
        $this->addSql('DROP INDEX IDX_140AB620A977936C ON page');
        $this->addSql('DROP INDEX IDX_140AB620727ACA70 ON page');
        $this->addSql('ALTER TABLE page DROP tree_root, DROP parent_id, DROP lft, DROP lvl, DROP rgt');
    }
}
