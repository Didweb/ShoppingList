<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250712145546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4128692');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4128692 FOREIGN KEY (canonical_item_id) REFERENCES item (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4128692');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4128692 FOREIGN KEY (canonical_item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
