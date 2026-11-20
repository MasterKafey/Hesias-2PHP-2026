<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261118110200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, format VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, capacity INT DEFAULT NULL, year INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, classroom_id INT NOT NULL, avatar_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id), INDEX IDX_8D93D6496278D5A8 (classroom_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_server (user_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_3F3FCECBA76ED395 (user_id), INDEX IDX_3F3FCECB1844E6B7 (server_id), PRIMARY KEY (user_id, server_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
        $this->addSql('ALTER TABLE user_server ADD CONSTRAINT FK_3F3FCECBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_server ADD CONSTRAINT FK_3F3FCECB1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496278D5A8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE user_server DROP FOREIGN KEY FK_3F3FCECBA76ED395');
        $this->addSql('ALTER TABLE user_server DROP FOREIGN KEY FK_3F3FCECB1844E6B7');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_server');
    }
}
