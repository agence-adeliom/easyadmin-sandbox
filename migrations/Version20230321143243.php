<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321143243 extends AbstractMigration
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
        $this->addSql('ALTER TABLE easy_block__block CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE easy_menu__menus_items DROP FOREIGN KEY FK_A119029D727ACA70');
        $this->addSql('ALTER TABLE easy_menu__menus_items ADD CONSTRAINT FK_A119029D727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_menu__menus_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE easy_page__page DROP FOREIGN KEY FK_2E074586727ACA70');
        $this->addSql('ALTER TABLE easy_page__page CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE easy_page__page ADD CONSTRAINT FK_2E074586727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_page__page (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE easy_post__category CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE easy_redirect__not_found ADD host VARCHAR(255) DEFAULT \'\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_1ADAE4A05F8A7F73 ON easy_redirect__redirect');
        $this->addSql('ALTER TABLE easy_redirect__redirect ADD host VARCHAR(255) DEFAULT \'\' NOT NULL, CHANGE source source VARCHAR(500) NOT NULL, CHANGE destination destination VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE media_entity ADD icon VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $isSqlite = class_exists(SqlitePlatform::class) && is_a($this->connection->getDatabasePlatform(), SqlitePlatform::class, true);
        $this->skipIf($isSqlite);
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE easy_block__block CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE easy_blog__post CHANGE name name VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__category CHANGE name name VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE easy_faq__entry CHANGE name name VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE easy_menu__menus_items DROP FOREIGN KEY FK_A119029D727ACA70');
        $this->addSql('ALTER TABLE easy_menu__menus_items ADD CONSTRAINT FK_A119029D727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_menu__menus_items (id)');
        $this->addSql('ALTER TABLE easy_page__page DROP FOREIGN KEY FK_2E074586727ACA70');
        $this->addSql('ALTER TABLE easy_page__page CHANGE name name VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE easy_page__page ADD CONSTRAINT FK_2E074586727ACA70 FOREIGN KEY (parent_id) REFERENCES easy_page__page (id)');
        $this->addSql('ALTER TABLE easy_post__category CHANGE name name VARCHAR(100) NOT NULL, CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE easy_redirect__not_found DROP host');
        $this->addSql('ALTER TABLE easy_redirect__redirect DROP host, CHANGE source source VARCHAR(255) NOT NULL, CHANGE destination destination VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAE4A05F8A7F73 ON easy_redirect__redirect (source)');
        $this->addSql('ALTER TABLE media_entity DROP icon');
    }
}
