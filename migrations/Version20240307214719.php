<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307214719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maladie ADD CONSTRAINT FK_ADC4024BA1799A53 FOREIGN KEY (id_medecin_id) REFERENCES medecin (id)');
        $this->addSql('CREATE INDEX IDX_ADC4024BA1799A53 ON maladie (id_medecin_id)');
        $this->addSql('ALTER TABLE pharmacie ADD loc VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maladie DROP FOREIGN KEY FK_ADC4024BA1799A53');
        $this->addSql('DROP INDEX IDX_ADC4024BA1799A53 ON maladie');
        $this->addSql('ALTER TABLE pharmacie DROP loc');
    }
}
