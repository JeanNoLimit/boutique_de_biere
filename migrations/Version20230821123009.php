<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821123009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beer_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, production_type_id INT NOT NULL, designation VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, stock INT DEFAULT NULL, available TINYINT(1) NOT NULL, volume DOUBLE PRECISION NOT NULL, ingredients LONGTEXT NOT NULL, alcohol_level DOUBLE PRECISION NOT NULL, bitterness DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04ADA53A8AA (provider_id), INDEX IDX_D34A04ADD059014E (production_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_beer_type (product_id INT NOT NULL, beer_type_id INT NOT NULL, INDEX IDX_EA8ABA794584665A (product_id), INDEX IDX_EA8ABA79A3829862 (beer_type_id), PRIMARY KEY(product_id, beer_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE production_type (id INT AUTO_INCREMENT NOT NULL, production_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, cp VARCHAR(10) NOT NULL, city VARCHAR(100) NOT NULL, website VARCHAR(255) DEFAULT NULL, social_network VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD059014E FOREIGN KEY (production_type_id) REFERENCES production_type (id)');
        $this->addSql('ALTER TABLE product_beer_type ADD CONSTRAINT FK_EA8ABA794584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_beer_type ADD CONSTRAINT FK_EA8ABA79A3829862 FOREIGN KEY (beer_type_id) REFERENCES beer_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA53A8AA');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD059014E');
        $this->addSql('ALTER TABLE product_beer_type DROP FOREIGN KEY FK_EA8ABA794584665A');
        $this->addSql('ALTER TABLE product_beer_type DROP FOREIGN KEY FK_EA8ABA79A3829862');
        $this->addSql('DROP TABLE beer_type');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_beer_type');
        $this->addSql('DROP TABLE production_type');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
