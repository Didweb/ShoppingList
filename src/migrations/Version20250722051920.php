<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722051920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A45970EE2FF6');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A45970EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A45970EE2FF6');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A45970EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
