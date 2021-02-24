<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223125848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tile ADD occupant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA90467BAA0E5 FOREIGN KEY (occupant_id) REFERENCES hero (id)');
        $this->addSql('CREATE INDEX IDX_768FA90467BAA0E5 ON tile (occupant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA90467BAA0E5');
        $this->addSql('DROP INDEX IDX_768FA90467BAA0E5 ON tile');
        $this->addSql('ALTER TABLE tile DROP occupant_id');
    }
}
