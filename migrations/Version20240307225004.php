<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307225004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chef_lab ADD is_blocked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE infirmier ADD is_blocked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE medecin ADD is_blocked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE patient ADD is_blocked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE pharmacien ADD is_blocked TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chef_lab DROP is_blocked');
        $this->addSql('ALTER TABLE infirmier DROP is_blocked');
        $this->addSql('ALTER TABLE medecin DROP is_blocked');
        $this->addSql('ALTER TABLE patient DROP is_blocked');
        $this->addSql('ALTER TABLE pharmacien DROP is_blocked');
    }
}
