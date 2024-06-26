<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626125831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE symptom_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE symptom (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE symptom_user (symptom_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(symptom_id, user_id))');
        $this->addSql('CREATE INDEX IDX_8ECE5BA5DEEFDA95 ON symptom_user (symptom_id)');
        $this->addSql('CREATE INDEX IDX_8ECE5BA5A76ED395 ON symptom_user (user_id)');
        $this->addSql('ALTER TABLE symptom_user ADD CONSTRAINT FK_8ECE5BA5DEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE symptom_user ADD CONSTRAINT FK_8ECE5BA5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE symptom_id_seq CASCADE');
        $this->addSql('ALTER TABLE symptom_user DROP CONSTRAINT FK_8ECE5BA5DEEFDA95');
        $this->addSql('ALTER TABLE symptom_user DROP CONSTRAINT FK_8ECE5BA5A76ED395');
        $this->addSql('DROP TABLE symptom');
        $this->addSql('DROP TABLE symptom_user');
    }
}
