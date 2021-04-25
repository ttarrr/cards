<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425225425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE card_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE card_type (id INT NOT NULL, title VARCHAR(255) NOT NULL, power INT NOT NULL, immortal BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_60ED558B2B36786B ON card_type (title)');
        $this->addSql('CREATE TABLE deck (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN deck.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE deck_entry (id UUID NOT NULL, card_type_id INT NOT NULL, deck_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA7C679F925606E5 ON deck_entry (card_type_id)');
        $this->addSql('CREATE INDEX IDX_EA7C679F111948DC ON deck_entry (deck_id)');
        $this->addSql('COMMENT ON COLUMN deck_entry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deck_entry.deck_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('ALTER TABLE deck_entry ADD CONSTRAINT FK_EA7C679F925606E5 FOREIGN KEY (card_type_id) REFERENCES card_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deck_entry ADD CONSTRAINT FK_EA7C679F111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679F925606E5');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679F111948DC');
        $this->addSql('DROP SEQUENCE card_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE card_type');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_entry');
        $this->addSql('DROP TABLE "user"');
    }
}
