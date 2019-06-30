<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601165432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE demands_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE demands (id INT NOT NULL, customerid_id INT DEFAULT NULL, numref VARCHAR(255) NOT NULL, datedemand TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, message TEXT NOT NULL, address VARCHAR(255) NOT NULL, linkgg VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, datedemanded TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D24062F476F04A3B ON demands (customerid_id)');
        $this->addSql('CREATE TABLE demands_type_of_customer (demands_id INT NOT NULL, type_of_customer_id INT NOT NULL, PRIMARY KEY(demands_id, type_of_customer_id))');
        $this->addSql('CREATE INDEX IDX_4346CBD8F59B565B ON demands_type_of_customer (demands_id)');
        $this->addSql('CREATE INDEX IDX_4346CBD845DAD96D ON demands_type_of_customer (type_of_customer_id)');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F476F04A3B FOREIGN KEY (customerid_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demands_type_of_customer ADD CONSTRAINT FK_4346CBD8F59B565B FOREIGN KEY (demands_id) REFERENCES demands (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demands_type_of_customer ADD CONSTRAINT FK_4346CBD845DAD96D FOREIGN KEY (type_of_customer_id) REFERENCES type_of_customer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE demands_type_of_customer DROP CONSTRAINT FK_4346CBD8F59B565B');
        $this->addSql('DROP SEQUENCE demands_id_seq CASCADE');
        $this->addSql('DROP TABLE demands');
        $this->addSql('DROP TABLE demands_type_of_customer');
    }
}
