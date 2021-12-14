<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213192543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD picture1 VARCHAR(255) NOT NULL, ADD picture2 VARCHAR(255) DEFAULT NULL, ADD picture3 VARCHAR(255) DEFAULT NULL, ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE customer ADD licence_picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP picture1, DROP picture2, DROP picture3, DROP price');
        $this->addSql('ALTER TABLE customer DROP licence_picture');
    }
}
