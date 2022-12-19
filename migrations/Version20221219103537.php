<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219103537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_follow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id BLOB NOT NULL --(DC2Type:uuid)
        , follower_id BLOB NOT NULL --(DC2Type:uuid)
        , follow_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D665F4DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D665F4DAC24F853 FOREIGN KEY (follower_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D665F4DA76ED395 ON user_follow (user_id)');
        $this->addSql('CREATE INDEX IDX_D665F4DAC24F853 ON user_follow (follower_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_follow');
    }
}
