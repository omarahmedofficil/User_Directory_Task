-- Experimental database structure

CREATE DATABASE IF NOT EXISTS employees_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE employees_test;

CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

INSERT INTO migrations (name) VALUES ('001_create_departments_table'), ('002_create_users_table');

INSERT INTO departments (name, code) VALUES
('Engineering', 'ENG'),
('Human Resources', 'HR'),
('Marketing', 'MKT'),
('Sales', 'SAL'),
('Finance', 'FIN');

INSERT INTO users (first_name, last_name, email, avatar_url, job_title, department_id) VALUES
('George', 'Bluth', 'george.bluth@employee.test', 'https://i.pravatar.cc/150?img=1', 'Senior Software Engineer', 1),
('Janet', 'Weaver', 'janet.weaver@employee.test', 'https://i.pravatar.cc/150?img=2', 'Backend Developer', 2),
('Emma', 'Wong', 'emma.wong@employee.test', 'https://i.pravatar.cc/150?img=3', 'Frontend Developer', 3),
('Eve', 'Holt', 'eve.holt@employee.test', 'https://i.pravatar.cc/150?img=4', 'QA Engineer', 4),
('Charles', 'Morris', 'charles.morris@employee.test', 'https://i.pravatar.cc/150?img=5', 'HR Specialist', 5),
('Tracey', 'Ramirez', 'tracey.ramirez@employee.test', 'https://i.pravatar.cc/150?img=6', 'Talent Acquisition Lead', 1),
('Michael', 'Lawson', 'michael.lawson@employee.test', 'https://i.pravatar.cc/150?img=7', 'Marketing Manager', 2),
('Lindsay', 'Ferguson', 'lindsay.ferguson@employee.test', 'https://i.pravatar.cc/150?img=8', 'Content Strategist', 3),
('Tobias', 'Funke', 'tobias.funke@employee.test', 'https://i.pravatar.cc/150?img=9', 'Sales Executive', 4),
('Byron', 'Baker', 'byron.baker@employee.test', 'https://i.pravatar.cc/150?img=10', 'Account Manager', 5),
('Rachel', 'Howard', 'rachel.howard@employee.test', 'https://i.pravatar.cc/150?img=11', 'Financial Analyst', 1),
('Rob', 'Edwards', 'rob.edwards@employee.test', 'https://i.pravatar.cc/150?img=12', 'Accountant', 2),
('Lucille', 'Bluth', 'lucille.bluth@employee.test', 'https://i.pravatar.cc/150?img=13', 'DevOps Engineer', 3),
('Lily', 'Marshall', 'lily.marshall@employee.test', 'https://i.pravatar.cc/150?img=14', 'Product Manager', 4),
('Gob', 'Bluth', 'gob.bluth@employee.test', 'https://i.pravatar.cc/150?img=15', 'UI/UX Designer', 5),
('Buster', 'Bluth', 'buster.bluth@employee.test', 'https://i.pravatar.cc/150?img=16', 'Senior Software Engineer', 1),
('Ann', 'Perkins', 'ann.perkins@employee.test', 'https://i.pravatar.cc/150?img=17', 'Backend Developer', 2),
('Steve', 'Rogers', 'steve.rogers@employee.test', 'https://i.pravatar.cc/150?img=18', 'Frontend Developer', 3),
('Sally', 'Kim', 'sally.kim@employee.test', 'https://i.pravatar.cc/150?img=19', 'QA Engineer', 4),
('Sarah', 'Fisher', 'sarah.fisher@employee.test', 'https://i.pravatar.cc/150?img=20', 'HR Specialist', 5),
('David', 'Cole', 'david.cole@employee.test', 'https://i.pravatar.cc/150?img=21', 'Talent Acquisition Lead', 1),
('Laura', 'Bennett', 'laura.bennett@employee.test', 'https://i.pravatar.cc/150?img=22', 'Marketing Manager', 2),
('Kevin', 'Turner', 'kevin.turner@employee.test', 'https://i.pravatar.cc/150?img=23', 'Content Strategist', 3),
('Nora', 'Bishop', 'nora.bishop@employee.test', 'https://i.pravatar.cc/150?img=24', 'Sales Executive', 4),
('Peter', 'Wells', 'peter.wells@employee.test', 'https://i.pravatar.cc/150?img=25', 'Account Manager', 5),
('Alice', 'Grant', 'alice.grant@employee.test', 'https://i.pravatar.cc/150?img=26', 'Financial Analyst', 1),
('Omar', 'Saleh', 'omar.saleh@employee.test', 'https://i.pravatar.cc/150?img=27', 'Accountant', 2),
('Nadia', 'Haddad', 'nadia.haddad@employee.test', 'https://i.pravatar.cc/150?img=28', 'DevOps Engineer', 3),
('Karim', 'Aziz', 'karim.aziz@employee.test', 'https://i.pravatar.cc/150?img=29', 'Product Manager', 4),
('Mona', 'Younes', 'mona.younes@employee.test', 'https://i.pravatar.cc/150?img=30', 'UI/UX Designer', 5),
('Youssef', 'Farid', 'youssef.farid@employee.test', 'https://i.pravatar.cc/150?img=31', 'Senior Software Engineer', 1),
('Hana', 'Kassem', 'hana.kassem@employee.test', 'https://i.pravatar.cc/150?img=32', 'Backend Developer', 2),
('Tarek', 'Nassar', 'tarek.nassar@employee.test', 'https://i.pravatar.cc/150?img=33', 'Frontend Developer', 3),
('Salma', 'Halabi', 'salma.halabi@employee.test', 'https://i.pravatar.cc/150?img=34', 'QA Engineer', 4),
('Adam', 'Mansour', 'adam.mansour@employee.test', 'https://i.pravatar.cc/150?img=35', 'HR Specialist', 5),
('Layla', 'Zaidan', 'layla.zaidan@employee.test', 'https://i.pravatar.cc/150?img=36', 'Talent Acquisition Lead', 1),
('Sami', 'Barakat', 'sami.barakat@employee.test', 'https://i.pravatar.cc/150?img=37', 'Marketing Manager', 2),
('Rania', 'Sabbagh', 'rania.sabbagh@employee.test', 'https://i.pravatar.cc/150?img=38', 'Content Strategist', 3),
('Fadi', 'Khalil', 'fadi.khalil@employee.test', 'https://i.pravatar.cc/150?img=39', 'Sales Executive', 4),
('Dina', 'Aoun', 'dina.aoun@employee.test', 'https://i.pravatar.cc/150?img=40', 'Account Manager', 5),
('Hassan', 'Fares', 'hassan.fares@employee.test', 'https://i.pravatar.cc/150?img=41', 'Financial Analyst', 1),
('Yara', 'Mikhail', 'yara.mikhail@employee.test', 'https://i.pravatar.cc/150?img=42', 'Accountant', 2),
('Amir', 'Sarkis', 'amir.sarkis@employee.test', 'https://i.pravatar.cc/150?img=43', 'DevOps Engineer', 3),
('Farah', 'Boutros', 'farah.boutros@employee.test', 'https://i.pravatar.cc/150?img=44', 'Product Manager', 4),
('Ziad', 'Chami', 'ziad.chami@employee.test', 'https://i.pravatar.cc/150?img=45', 'UI/UX Designer', 5),
('Reem', 'Haidar', 'reem.haidar@employee.test', 'https://i.pravatar.cc/150?img=46', 'Senior Software Engineer', 1),
('Nabil', 'Rahal', 'nabil.rahal@employee.test', 'https://i.pravatar.cc/150?img=47', 'Backend Developer', 2),
('Maya', 'Jaber', 'maya.jaber@employee.test', 'https://i.pravatar.cc/150?img=48', 'Frontend Developer', 3),
('Wael', 'Deeb', 'wael.deeb@employee.test', 'https://i.pravatar.cc/150?img=49', 'QA Engineer', 4),
('Noor', 'Ghanem', 'noor.ghanem@employee.test', 'https://i.pravatar.cc/150?img=50', 'HR Specialist', 5);