<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107122426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD lastname VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, DROP name, DROP adress, DROP email');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E094FBF094F ON customer (company)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_81398E094FBF094F ON customer');
        $this->addSql('ALTER TABLE customer ADD name VARCHAR(255) NOT NULL, ADD adress VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, DROP lastname, DROP address, DROP email');
    }
}
