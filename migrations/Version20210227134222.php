<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227134222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE furniture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, direction VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tile ADD furniture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA904CF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
        $this->addSql('CREATE INDEX IDX_768FA904CF5485C3 ON tile (furniture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA904CF5485C3');
        $this->addSql('DROP TABLE furniture');
        $this->addSql('DROP INDEX IDX_768FA904CF5485C3 ON tile');
        $this->addSql('ALTER TABLE tile DROP furniture_id');
    }
}
