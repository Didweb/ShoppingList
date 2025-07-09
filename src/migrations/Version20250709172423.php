<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709172423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_1F1B251EB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_list (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, circle_id INT NOT NULL, name VARCHAR(100) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3DC1A459B03A8386 (created_by_id), INDEX IDX_3DC1A45970EE2FF6 (circle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_list_item (id INT AUTO_INCREMENT NOT NULL, shopping_list_id INT NOT NULL, item_id INT NOT NULL, added_by_id INT NOT NULL, status VARCHAR(50) NOT NULL COMMENT \'(DC2Type:status)\', added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4FB1C22423245BF9 (shopping_list_id), INDEX IDX_4FB1C224126F525E (item_id), INDEX IDX_4FB1C22455B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A459B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A45970EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
        $this->addSql('ALTER TABLE shopping_list_item ADD CONSTRAINT FK_4FB1C22423245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id)');
        $this->addSql('ALTER TABLE shopping_list_item ADD CONSTRAINT FK_4FB1C224126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE shopping_list_item ADD CONSTRAINT FK_4FB1C22455B127A4 FOREIGN KEY (added_by_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EB03A8386');
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A459B03A8386');
        $this->addSql('ALTER TABLE shopping_list DROP FOREIGN KEY FK_3DC1A45970EE2FF6');
        $this->addSql('ALTER TABLE shopping_list_item DROP FOREIGN KEY FK_4FB1C22423245BF9');
        $this->addSql('ALTER TABLE shopping_list_item DROP FOREIGN KEY FK_4FB1C224126F525E');
        $this->addSql('ALTER TABLE shopping_list_item DROP FOREIGN KEY FK_4FB1C22455B127A4');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('DROP TABLE shopping_list_item');
    }
}
