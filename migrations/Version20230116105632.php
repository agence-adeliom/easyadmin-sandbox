<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116105632 extends AbstractMigration
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
        $this->addSql('ALTER TABLE easy_menu__menus_items DROP FOREIGN KEY FK_A119029D727ACA70');
        $this->addSql('ALTER TABLE easy_menu__menus_items ADD CONSTRAINT FK_A119029D727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_menu__menus_items (id)');
    }

    public function down(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf($isSqlite);
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_menu__menus_items DROP FOREIGN KEY FK_A119029D727ACA70');
        $this->addSql('ALTER TABLE easy_menu__menus_items ADD CONSTRAINT FK_A119029D727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_menu__menus_items (id) ON DELETE CASCADE');
    }
}
