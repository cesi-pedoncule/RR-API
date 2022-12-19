<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219103248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_like (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id BLOB NOT NULL --(DC2Type:uuid)
        , resource_id BLOB NOT NULL --(DC2Type:uuid)
        , like_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D6E20C7AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D6E20C7A89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D6E20C7AA76ED395 ON user_like (user_id)');
        $this->addSql('CREATE INDEX IDX_D6E20C7A89329D25 ON user_like (resource_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_like');
    }
}
