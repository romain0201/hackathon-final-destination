<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626060844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel ADD medicine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE channel ADD patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E472F7D140A FOREIGN KEY (medicine_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E476B899279 FOREIGN KEY (patient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A2F98E472F7D140A ON channel (medicine_id)');
        $this->addSql('CREATE INDEX IDX_A2F98E476B899279 ON channel (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E472F7D140A');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E476B899279');
        $this->addSql('DROP INDEX IDX_A2F98E472F7D140A');
        $this->addSql('DROP INDEX IDX_A2F98E476B899279');
        $this->addSql('ALTER TABLE channel DROP medicine_id');
        $this->addSql('ALTER TABLE channel DROP patient_id');
    }
}
