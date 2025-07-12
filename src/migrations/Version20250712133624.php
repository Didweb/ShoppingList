<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250712133624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE item ADD canonical_item_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4128692 FOREIGN KEY (canonical_item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E4128692 ON item (canonical_item_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4128692');
        $this->addSql('DROP INDEX IDX_1F1B251E4128692 ON item');
        $this->addSql('ALTER TABLE item DROP canonical_item_id');
    }
}
