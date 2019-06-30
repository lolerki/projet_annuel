<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190528172003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE customers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customers (id INT NOT NULL, user_id_id INT DEFAULT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(100) NOT NULL, fullname VARCHAR(200) NOT NULL, status VARCHAR(50) NOT NULL, gender VARCHAR(255) NOT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT NOT NULL, address TEXT NOT NULL, city VARCHAR(255) NOT NULL, country_code VARCHAR(255) NOT NULL, numberfix INT DEFAULT NULL, numbermob INT DEFAULT NULL, discipline VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, artist_verify INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62534E219D86650F ON customers (user_id_id)');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E219D86650F FOREIGN KEY (user_id_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE customers_id_seq CASCADE');
        $this->addSql('DROP TABLE customers');
    }
}
