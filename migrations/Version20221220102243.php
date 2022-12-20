<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220102243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE state_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_follow_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_like_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attachment (id UUID NOT NULL, user_id UUID NOT NULL, resource_id UUID NOT NULL, file_path VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_795FD9BBA76ED395 ON attachment (user_id)');
        $this->addSql('CREATE INDEX IDX_795FD9BB89329D25 ON attachment (resource_id)');
        $this->addSql('COMMENT ON COLUMN attachment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN attachment.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN attachment.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN attachment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category (id UUID NOT NULL, creator_id UUID NOT NULL, name VARCHAR(255) NOT NULL, is_visible BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C161220EA6 ON category (creator_id)');
        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category.creator_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment (id UUID NOT NULL, user_id UUID NOT NULL, resource_id UUID DEFAULT NULL, comment TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C89329D25 ON comment (resource_id)');
        $this->addSql('COMMENT ON COLUMN comment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE resource (id UUID NOT NULL, user_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_public BOOLEAN DEFAULT true NOT NULL, is_deleted BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC91F416A76ED395 ON resource (user_id)');
        $this->addSql('COMMENT ON COLUMN resource.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN resource.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN resource.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN resource.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE resource_category (resource_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY(resource_id, category_id))');
        $this->addSql('CREATE INDEX IDX_A8C0D36C89329D25 ON resource_category (resource_id)');
        $this->addSql('CREATE INDEX IDX_A8C0D36C12469DE2 ON resource_category (category_id)');
        $this->addSql('COMMENT ON COLUMN resource_category.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN resource_category.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE state (id INT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_banned BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_follow (id INT NOT NULL, user_id UUID NOT NULL, follower_id UUID NOT NULL, follow_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D665F4DA76ED395 ON user_follow (user_id)');
        $this->addSql('CREATE INDEX IDX_D665F4DAC24F853 ON user_follow (follower_id)');
        $this->addSql('COMMENT ON COLUMN user_follow.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_follow.follower_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_follow.follow_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_like (id INT NOT NULL, user_id UUID NOT NULL, resource_id UUID NOT NULL, like_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D6E20C7AA76ED395 ON user_like (user_id)');
        $this->addSql('CREATE INDEX IDX_D6E20C7A89329D25 ON user_like (resource_id)');
        $this->addSql('COMMENT ON COLUMN user_like.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_like.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_like.like_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE validation_state (id UUID NOT NULL, state_id INT NOT NULL, moderator_id UUID DEFAULT NULL, resource_id UUID DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B80D7E85D83CC1 ON validation_state (state_id)');
        $this->addSql('CREATE INDEX IDX_4B80D7E8D0AFA354 ON validation_state (moderator_id)');
        $this->addSql('CREATE INDEX IDX_4B80D7E889329D25 ON validation_state (resource_id)');
        $this->addSql('COMMENT ON COLUMN validation_state.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN validation_state.moderator_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN validation_state.resource_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN validation_state.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C161220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_category ADD CONSTRAINT FK_A8C0D36C89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_category ADD CONSTRAINT FK_A8C0D36C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_follow ADD CONSTRAINT FK_D665F4DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_follow ADD CONSTRAINT FK_D665F4DAC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_like ADD CONSTRAINT FK_D6E20C7AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_like ADD CONSTRAINT FK_D6E20C7A89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE validation_state ADD CONSTRAINT FK_4B80D7E85D83CC1 FOREIGN KEY (state_id) REFERENCES state (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE validation_state ADD CONSTRAINT FK_4B80D7E8D0AFA354 FOREIGN KEY (moderator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE validation_state ADD CONSTRAINT FK_4B80D7E889329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE state_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_follow_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_like_id_seq CASCADE');
        $this->addSql('ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BBA76ED395');
        $this->addSql('ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BB89329D25');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C161220EA6');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C89329D25');
        $this->addSql('ALTER TABLE resource DROP CONSTRAINT FK_BC91F416A76ED395');
        $this->addSql('ALTER TABLE resource_category DROP CONSTRAINT FK_A8C0D36C89329D25');
        $this->addSql('ALTER TABLE resource_category DROP CONSTRAINT FK_A8C0D36C12469DE2');
        $this->addSql('ALTER TABLE user_follow DROP CONSTRAINT FK_D665F4DA76ED395');
        $this->addSql('ALTER TABLE user_follow DROP CONSTRAINT FK_D665F4DAC24F853');
        $this->addSql('ALTER TABLE user_like DROP CONSTRAINT FK_D6E20C7AA76ED395');
        $this->addSql('ALTER TABLE user_like DROP CONSTRAINT FK_D6E20C7A89329D25');
        $this->addSql('ALTER TABLE validation_state DROP CONSTRAINT FK_4B80D7E85D83CC1');
        $this->addSql('ALTER TABLE validation_state DROP CONSTRAINT FK_4B80D7E8D0AFA354');
        $this->addSql('ALTER TABLE validation_state DROP CONSTRAINT FK_4B80D7E889329D25');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_category');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_follow');
        $this->addSql('DROP TABLE user_like');
        $this->addSql('DROP TABLE validation_state');
    }
}
