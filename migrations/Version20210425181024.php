<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425181024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deck (id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN deck.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE deck_entry (id UUID NOT NULL, card_type_id INT NOT NULL, deck_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA7C679F925606E5 ON deck_entry (card_type_id)');
        $this->addSql('CREATE INDEX IDX_EA7C679F111948DC ON deck_entry (deck_id)');
        $this->addSql('COMMENT ON COLUMN deck_entry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deck_entry.deck_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE deck_entry ADD CONSTRAINT FK_EA7C679F925606E5 FOREIGN KEY (card_type_id) REFERENCES card_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deck_entry ADD CONSTRAINT FK_EA7C679F111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE deck_entry DROP CONSTRAINT FK_EA7C679F111948DC');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_entry');
    }
}
