<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709163524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE circle (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(100) NOT NULL, color VARCHAR(9) NOT NULL, qr LONGTEXT NOT NULL, INDEX IDX_D4B76579B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circle_user (circle_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DC9CB5370EE2FF6 (circle_id), INDEX IDX_DC9CB53A76ED395 (user_id), PRIMARY KEY(circle_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE circle ADD CONSTRAINT FK_D4B76579B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE circle_user ADD CONSTRAINT FK_DC9CB5370EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE circle_user ADD CONSTRAINT FK_DC9CB53A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circle DROP FOREIGN KEY FK_D4B76579B03A8386');
        $this->addSql('ALTER TABLE circle_user DROP FOREIGN KEY FK_DC9CB5370EE2FF6');
        $this->addSql('ALTER TABLE circle_user DROP FOREIGN KEY FK_DC9CB53A76ED395');
        $this->addSql('DROP TABLE circle');
        $this->addSql('DROP TABLE circle_user');
    }
}
