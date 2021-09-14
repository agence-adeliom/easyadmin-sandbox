<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914065143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE easy_redirect__not_found (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, full_url VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, referer VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE easy_redirect__redirect (id INT AUTO_INCREMENT NOT NULL, source VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, status VARCHAR(10) NOT NULL, count INT NOT NULL, last_accessed DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1ADAE4A05F8A7F73 (source), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE easy_redirect__not_found');
        $this->addSql('DROP TABLE easy_redirect__redirect');
        $this->addSql('ALTER TABLE media_entity CHANGE file file TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
