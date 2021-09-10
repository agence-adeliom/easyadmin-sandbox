<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910074353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE menus ADD code VARCHAR(30) NOT NULL, ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menus_items ADD parent_id INT UNSIGNED DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD url VARCHAR(255) DEFAULT NULL, ADD class_attribute VARCHAR(255) DEFAULT NULL, ADD position SMALLINT UNSIGNED DEFAULT NULL, ADD target TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE menus_items ADD CONSTRAINT FK_B07C0EE1727ACA70 FOREIGN KEY (parent_id) REFERENCES menus_items (id)');
        $this->addSql('CREATE INDEX IDX_B07C0EE1727ACA70 ON menus_items (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE menus DROP code, DROP name');
        $this->addSql('ALTER TABLE menus_items DROP FOREIGN KEY FK_B07C0EE1727ACA70');
        $this->addSql('DROP INDEX IDX_B07C0EE1727ACA70 ON menus_items');
        $this->addSql('ALTER TABLE menus_items DROP parent_id, DROP name, DROP url, DROP class_attribute, DROP position, DROP target');
    }
}
