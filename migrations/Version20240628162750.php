<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240628162750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE statistic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE symptom_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE channel (id UUID NOT NULL, medicine_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, lock BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A2F98E472F7D140A ON channel (medicine_id)');
        $this->addSql('CREATE INDEX IDX_A2F98E476B899279 ON channel (patient_id)');
        $this->addSql('COMMENT ON COLUMN channel.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE message (id UUID NOT NULL, author_id INT DEFAULT NULL, channel_id UUID DEFAULT NULL, content TEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FF675F31B ON message (author_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F72F5A1AA ON message (channel_id)');
        $this->addSql('COMMENT ON COLUMN message.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN message.channel_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, client_id INT DEFAULT NULL, pharmacy_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, preparation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, order_items JSON NOT NULL, file VARCHAR(255) NOT NULL, doctor VARCHAR(255) NOT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F529939819EB6921 ON "order" (client_id)');
        $this->addSql('CREATE INDEX IDX_F52993988A94ABE2 ON "order" (pharmacy_id)');
        $this->addSql('COMMENT ON COLUMN "order".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "order".preparation_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE statistic (id INT NOT NULL, type VARCHAR(255) NOT NULL, data TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE symptom (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE symptom_user (symptom_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(symptom_id, user_id))');
        $this->addSql('CREATE INDEX IDX_8ECE5BA5DEEFDA95 ON symptom_user (symptom_id)');
        $this->addSql('CREATE INDEX IDX_8ECE5BA5A76ED395 ON symptom_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, phone VARCHAR(20) DEFAULT NULL, odp VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E472F7D140A FOREIGN KEY (medicine_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E476B899279 FOREIGN KEY (patient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993988A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE symptom_user ADD CONSTRAINT FK_8ECE5BA5DEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE symptom_user ADD CONSTRAINT FK_8ECE5BA5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE statistic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE symptom_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E472F7D140A');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E476B899279');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F72F5A1AA');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939819EB6921');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993988A94ABE2');
        $this->addSql('ALTER TABLE symptom_user DROP CONSTRAINT FK_8ECE5BA5DEEFDA95');
        $this->addSql('ALTER TABLE symptom_user DROP CONSTRAINT FK_8ECE5BA5A76ED395');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP TABLE symptom');
        $this->addSql('DROP TABLE symptom_user');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
