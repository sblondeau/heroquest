<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304123813 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE furniture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, direction VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero (id INT AUTO_INCREMENT NOT NULL, has_played_this_turn TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, remaining_move INT NOT NULL, move_dice_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, color VARCHAR(255) NOT NULL, is_visited TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tile (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, occupant_id INT DEFAULT NULL, furniture_id INT DEFAULT NULL, x INT NOT NULL, y INT NOT NULL, north VARCHAR(255) DEFAULT NULL, east VARCHAR(255) DEFAULT NULL, south VARCHAR(255) DEFAULT NULL, west VARCHAR(255) DEFAULT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_768FA90454177093 (room_id), UNIQUE INDEX UNIQ_768FA90467BAA0E5 (occupant_id), INDEX IDX_768FA904CF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA90454177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA90467BAA0E5 FOREIGN KEY (occupant_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE tile ADD CONSTRAINT FK_768FA904CF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA904CF5485C3');
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA90467BAA0E5');
        $this->addSql('ALTER TABLE tile DROP FOREIGN KEY FK_768FA90454177093');
        $this->addSql('DROP TABLE furniture');
        $this->addSql('DROP TABLE hero');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE tile');
    }
}
