<?php

/* MySQL database settings */

declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: 'employees_test',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
    'debug' => getenv('APP_DEBUG') !== false ? filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) : true,
];
