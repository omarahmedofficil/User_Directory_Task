<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/Autoload.php';

use App\Config\Database;

$pdo = Database::connection();

$pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL);

$applied = $pdo->query('SELECT name FROM migrations')->fetchAll(PDO::FETCH_COLUMN);

$files = glob(__DIR__ . '/migrations/*.php');
sort($files);

foreach ($files as $file) {
    $migration = require $file;

    if (in_array($migration['name'], $applied, true)) {
        echo "Skipping already-applied migration: {$migration['name']}\n";
        continue;
    }

    $pdo->exec($migration['up']);

    $stmt = $pdo->prepare('INSERT INTO migrations (name) VALUES (:name)');
    $stmt->execute([':name' => $migration['name']]);

    echo "Applied migration: {$migration['name']}\n";
}

echo "Migrations complete.\n";
