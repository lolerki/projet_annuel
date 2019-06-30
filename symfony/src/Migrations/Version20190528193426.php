<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190528193426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE events (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, date_event TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address VARCHAR(255) NOT NULL, linkgg VARCHAR(255) NOT NULL, style_event VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE events_customers (events_id INT NOT NULL, customers_id INT NOT NULL, PRIMARY KEY(events_id, customers_id))');
        $this->addSql('CREATE INDEX IDX_FBA72B039D6A1065 ON events_customers (events_id)');
        $this->addSql('CREATE INDEX IDX_FBA72B03C3568B40 ON events_customers (customers_id)');
        $this->addSql('ALTER TABLE events_customers ADD CONSTRAINT FK_FBA72B039D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events_customers ADD CONSTRAINT FK_FBA72B03C3568B40 FOREIGN KEY (customers_id) REFERENCES customers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE events_customers DROP CONSTRAINT FK_FBA72B039D6A1065');
        $this->addSql('DROP SEQUENCE events_id_seq CASCADE');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE events_customers');
    }
}
