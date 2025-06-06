<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125125244 extends AbstractMigration
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
        $this->addSql('ALTER TABLE media_entity ADD icon VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf($isSqlite);
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_entity DROP icon');
    }
}
