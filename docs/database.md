# User-service database

Run database commands in the same container and environment as the API:

    docker compose exec -T user-service php bin/console doctrine:migrations:status
    docker compose exec -T user-service php bin/console doctrine:query:sql "SELECT current_database(), current_schema()"
    docker compose exec -T user-service php bin/console doctrine:migrations:migrate --no-interaction
    docker compose exec -T user-service php bin/console doctrine:schema:validate

The running service currently targets user_db. POSTGRES_DB in Compose only
selects the database initialized by Postgres; Doctrine connects using DATABASE_URL.

Version20261002190319 creates the tables. Version20261006144558 makes updated_at
nullable. Four later copies of the baseline are retained as no-op versions so
fresh databases can migrate without repeated CREATE TABLE statements and existing
version identifiers remain valid. Their rollback does not drop tables.

For an existing database with divergent migration history, inspect the schema
before changing migration metadata. Never mark an unapplied migration as executed
to hide a missing table.

The Compose Postgres service has no explicitly named data volume. This correction
does not recreate containers or remove volumes.
