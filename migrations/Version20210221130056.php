<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210221130056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tile ADD room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA90454177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('CREATE INDEX IDX_768FA90454177093 ON tile (room_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA90454177093');
        $this->addSql('DROP INDEX IDX_768FA90454177093 ON tile');
        $this->addSql('ALTER TABLE tile DROP room_id');
    }
}
