<?php

/* Database connection for MySQL. */

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/config.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset'],
            );

            try {
                $pdo = new PDO($dsn, $config['username'], $config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            } catch (PDOException $e) {
                throw new PDOException(
                    'Database connection failed: ' . $e->getMessage() .
                    '. Make sure XAMPP (Apache + MySQL) is running and the "' .
                    $config['database'] . '" database exists.'
                );
            }

            self::$connection = $pdo;
        }

        return self::$connection;
    }
}
