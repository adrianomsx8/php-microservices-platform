<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Retained to preserve existing migration version identifiers.
 * Table creation belongs to Version20261002190319; nullable updated_at
 * belongs to Version20261006144558.
 */
final class Version20261008192301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Legacy duplicate baseline: schema is managed by the original migrations.';
    }

    public function up(Schema $schema): void
    {
        // Intentionally empty: do not recreate existing tables.
    }

    public function down(Schema $schema): void
    {
        // This version owns no schema changes; never drop another version's tables.
    }
}
