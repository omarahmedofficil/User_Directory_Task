<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/Autoload.php';

use App\Config\Database;

$pdo = Database::connection();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE users');
$pdo->exec('TRUNCATE TABLE departments');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$departments = [
    ['name' => 'Engineering', 'code' => 'ENG'],
    ['name' => 'Human Resources', 'code' => 'HR'],
    ['name' => 'Marketing', 'code' => 'MKT'],
    ['name' => 'Sales', 'code' => 'SAL'],
    ['name' => 'Finance', 'code' => 'FIN'],
];

$insertDept = $pdo->prepare('INSERT INTO departments (name, code) VALUES (:name, :code)');
foreach ($departments as $dept) {
    $insertDept->execute([':name' => $dept['name'], ':code' => $dept['code']]);
}

$departmentIds = $pdo->query('SELECT id FROM departments ORDER BY id ASC')->fetchAll(PDO::FETCH_COLUMN);

$firstNames = ['George', 'Janet', 'Emma', 'Eve', 'Charles', 'Tracey', 'Michael', 'Lindsay', 'Tobias', 'Byron',
    'Rachel', 'Rob', 'Lucille', 'Lily', 'Gob', 'Buster', 'Ann', 'Steve', 'Sally', 'Sarah',
    'David', 'Laura', 'Kevin', 'Nora', 'Peter', 'Alice', 'Omar', 'Nadia', 'Karim', 'Mona',
    'Youssef', 'Hana', 'Tarek', 'Salma', 'Adam', 'Layla', 'Sami', 'Rania', 'Fadi', 'Dina',
    'Hassan', 'Yara', 'Amir', 'Farah', 'Ziad', 'Reem', 'Nabil', 'Maya', 'Wael', 'Noor'];

$lastNames = ['Bluth', 'Weaver', 'Wong', 'Holt', 'Morris', 'Ramirez', 'Lawson', 'Ferguson', 'Funke', 'Baker',
    'Howard', 'Edwards', 'Bluth', 'Marshall', 'Bluth', 'Bluth', 'Perkins', 'Rogers', 'Kim', 'Fisher',
    'Cole', 'Bennett', 'Turner', 'Bishop', 'Wells', 'Grant', 'Saleh', 'Haddad', 'Aziz', 'Younes',
    'Farid', 'Kassem', 'Nassar', 'Halabi', 'Mansour', 'Zaidan', 'Barakat', 'Sabbagh', 'Khalil', 'Aoun',
    'Fares', 'Mikhail', 'Sarkis', 'Boutros', 'Chami', 'Haidar', 'Rahal', 'Jaber', 'Deeb', 'Ghanem'];

$jobTitles = ['Senior Software Engineer', 'Backend Developer', 'Frontend Developer', 'QA Engineer',
    'HR Specialist', 'Talent Acquisition Lead', 'Marketing Manager', 'Content Strategist',
    'Sales Executive', 'Account Manager', 'Financial Analyst', 'Accountant', 'DevOps Engineer',
    'Product Manager', 'UI/UX Designer'];

$insertUser = $pdo->prepare(
    'INSERT INTO users (first_name, last_name, email, avatar_url, job_title, department_id)
     VALUES (:first_name, :last_name, :email, :avatar_url, :job_title, :department_id)'
);

$usedEmails = [];

for ($i = 0; $i < 50; $i++) {
    $firstName = $firstNames[$i % count($firstNames)];
    $lastName = $lastNames[$i % count($lastNames)];
    $baseEmail = strtolower($firstName . '.' . $lastName);
    $email = $baseEmail . '@infinitytec.test';

    $suffix = 1;
    while (in_array($email, $usedEmails, true)) {
        $email = $baseEmail . $suffix . '@infinitytec.test';
        $suffix++;
    }
    $usedEmails[] = $email;

    $insertUser->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':email' => $email,
        ':avatar_url' => 'https://i.pravatar.cc/150?img=' . (($i % 70) + 1),
        ':job_title' => $jobTitles[$i % count($jobTitles)],
        ':department_id' => $departmentIds[$i % count($departmentIds)],
    ]);
}

echo "Seeded " . count($departments) . " departments and 50 users.\n";
