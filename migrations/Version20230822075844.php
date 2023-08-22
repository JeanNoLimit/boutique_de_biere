<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822075844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beer_type CHANGE name name VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE designation designation VARCHAR(100) NOT NULL, CHANGE bitterness bitterness DOUBLE PRECISION DEFAULT NULL, CHANGE slug slug VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE production_type CHANGE production_type production_type VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE provider CHANGE name name VARCHAR(50) NOT NULL, CHANGE adress adress VARCHAR(150) NOT NULL, CHANGE city city VARCHAR(50) NOT NULL, CHANGE website website VARCHAR(200) DEFAULT NULL, CHANGE social_network social_network VARCHAR(200) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beer_type CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE designation designation VARCHAR(255) NOT NULL, CHANGE bitterness bitterness DOUBLE PRECISION NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE production_type CHANGE production_type production_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE provider CHANGE name name VARCHAR(255) NOT NULL, CHANGE adress adress VARCHAR(255) NOT NULL, CHANGE city city VARCHAR(100) NOT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL, CHANGE social_network social_network VARCHAR(255) DEFAULT NULL');
    }
}
