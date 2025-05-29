<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529193557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender_id INTEGER NOT NULL, reciever_id INTEGER NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6BD307F5D5C928D FOREIGN KEY (reciever_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307F5D5C928D ON message (reciever_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
    }
}
