<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251022051920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Importa datos de listas.item desde un archivo SQL externo';
    }

    // IMPORTANTE: desactivar la transacción automática si tu .sql lleva START/COMMIT
    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $sqlFile = __DIR__ . '/sql/item_import_keep_canonical.sql'; // pon tu archivo aquí

        if (!file_exists($sqlFile)) {
            throw new \RuntimeException("No se encontró el archivo SQL: $sqlFile");
        }

        $sql = file_get_contents($sqlFile);

        // Divide por ';' y ejecuta cada sentencia (DBAL no acepta multi-statement en bloque)
        $statements = array_filter(array_map('trim', preg_split('/;[\r\n]*/', $sql)));
        foreach ($statements as $statement) {
            // omitir comentarios y líneas vacías
            if ($statement === '' || str_starts_with($statement, '--') || str_starts_with($statement, '/*')) {
                continue;
            }
            $this->addSql($statement);
        }
    }

    public function down(Schema $schema): void
    {
         $this->addSql("DELETE FROM listas.item WHERE created_by_id IS NULL;");
    }
}
