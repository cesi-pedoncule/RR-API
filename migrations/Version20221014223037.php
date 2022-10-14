<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014223037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment (id BLOB NOT NULL --(DC2Type:uuid)
        , user_id BLOB NOT NULL --(DC2Type:uuid)
        , resource_id BLOB NOT NULL --(DC2Type:uuid)
        , filename VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_795FD9BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_795FD9BB89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_795FD9BBA76ED395 ON attachment (user_id)');
        $this->addSql('CREATE INDEX IDX_795FD9BB89329D25 ON attachment (resource_id)');
        $this->addSql('CREATE TABLE category (id BLOB NOT NULL --(DC2Type:uuid)
        , creator_id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, is_visible BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_64C19C161220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_64C19C161220EA6 ON category (creator_id)');
        $this->addSql('CREATE TABLE comment (id BLOB NOT NULL --(DC2Type:uuid)
        , user_id BLOB NOT NULL --(DC2Type:uuid)
        , resource_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , comment CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526C89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C89329D25 ON comment (resource_id)');
        $this->addSql('CREATE TABLE resource (id BLOB NOT NULL --(DC2Type:uuid)
        , user_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , category_id BLOB NOT NULL --(DC2Type:uuid)
        , validation_state_id BLOB NOT NULL --(DC2Type:uuid)
        , title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_public BOOLEAN NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_BC91F416A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BC91F41612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BC91F416E271949B FOREIGN KEY (validation_state_id) REFERENCES validation_state (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BC91F416A76ED395 ON resource (user_id)');
        $this->addSql('CREATE INDEX IDX_BC91F41612469DE2 ON resource (category_id)');
        $this->addSql('CREATE INDEX IDX_BC91F416E271949B ON resource (validation_state_id)');
        $this->addSql('CREATE TABLE user (id BLOB NOT NULL --(DC2Type:uuid)
        , email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_banned BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE validation_state (id BLOB NOT NULL --(DC2Type:uuid)
        , moderator_id BLOB NOT NULL --(DC2Type:uuid)
        , state INTEGER NOT NULL, updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id), CONSTRAINT FK_4B80D7E8D0AFA354 FOREIGN KEY (moderator_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4B80D7E8D0AFA354 ON validation_state (moderator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE validation_state');
    }
}
