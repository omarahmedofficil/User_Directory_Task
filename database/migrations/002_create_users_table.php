<?php
declare(strict_types=1);

return [
    'name' => '002_create_users_table',
    'up' => <<<SQL
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            avatar_url VARCHAR(500) NULL,
            job_title VARCHAR(150) NOT NULL,
            department_id INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_users_department FOREIGN KEY (department_id) REFERENCES departments(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    SQL,
];
