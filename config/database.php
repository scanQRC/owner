<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/*
|--------------------------------------------------------------------------
| Database Helper Functions
|--------------------------------------------------------------------------
*/

function db(): PDO
{
    global $pdo;

    return $pdo;
}

function db_query(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);

    $stmt->execute($params);

    return $stmt;
}

function db_fetch(string $sql, array $params = []): array|false
{
    return db_query($sql, $params)->fetch();
}

function db_fetch_all(string $sql, array $params = []): array
{
    return db_query($sql, $params)->fetchAll();
}

function db_insert(string $sql, array $params = []): int
{
    db_query($sql, $params);

    return (int) db()->lastInsertId();
}

function db_execute(string $sql, array $params = []): bool
{
    return db_query($sql, $params)->rowCount() > 0;
}

function db_exists(string $sql, array $params = []): bool
{
    return db_query($sql, $params)->fetchColumn() !== false;
}

function db_count(string $sql, array $params = []): int
{
    return (int) db_query($sql, $params)->fetchColumn();
}

function db_begin(): bool
{
    return db()->beginTransaction();
}

function db_commit(): bool
{
    return db()->commit();
}

function db_rollback(): bool
{
    if (db()->inTransaction()) {
        return db()->rollBack();
    }

    return false;
}
