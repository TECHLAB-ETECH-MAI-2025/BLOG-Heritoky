<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517174955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__comment AS SELECT id, article_id, author, content, create_at FROM comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER DEFAULT NULL, author VARCHAR(255) NOT NULL, content CLOB NOT NULL, create_at VARCHAR(255) NOT NULL, CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO comment (id, article_id, author, content, create_at) SELECT id, article_id, author, content, create_at FROM __temp__comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__comment
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C7294869C ON comment (article_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__comment AS SELECT id, article_id, author, content, create_at FROM comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER DEFAULT NULL, author VARCHAR(255) NOT NULL, content CLOB NOT NULL, create_at DATETIME NOT NULL, CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO comment (id, article_id, author, content, create_at) SELECT id, article_id, author, content, create_at FROM __temp__comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__comment
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9474526C7294869C ON comment (article_id)
        SQL);
    }
}
