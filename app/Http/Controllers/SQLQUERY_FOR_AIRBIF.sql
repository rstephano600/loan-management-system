-- 20/04/2026
ALTER TABLE users
DROP COLUMN status;

ALTER TABLE users
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER remember_token;

UPDATE users
SET User_id = created_by;
UPDATE users
SET Status = status;

ALTER TABLE users
DROP COLUMN status;

ALTER TABLE users
ADD CONSTRAINT users_user_fk
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE users
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id;

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    Status VARCHAR(20) DEFAULT 'Active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE permission_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    User_id BIGINT UNSIGNED,
    permission_id BIGINT UNSIGNED,
    Creater_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(20) DEFAULT 'Active',
    duration ENUM('Permanent', 'Temporary') NOT NULL DEFAULT 'Permanent',
    start_date DATE NULL,
    end_date DATE NULL,

    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (Creater_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('Company Admin', 'is-company-admin','company admin can manage everything of the company', 'Active', NOW(), NOW()),
('Company Director', 'is-company-director','Given to Director of the  Company', 'Active', NOW(), NOW()),
('Company CEO', 'is-company-ceo','Given to CEO of the  Company', 'Active', NOW(), NOW()),
('Company Shareholder', 'is-company-shareholder','Given to shareholders of the  Company', 'Active', NOW(), NOW()),
('Company Manager', 'is-company-manager','Given to manager of the  Company', 'Active', NOW(), NOW()),
('Company Human Resources', 'is-company-hr','Given to Human Resources of the  Company', 'Active', NOW(), NOW()),
('Company Accountant', 'is-company-accountant','Given to accountant of the  Company', 'Active', NOW(), NOW()),
('Company Marketingofficer', 'is-company-marketingofficer','Given to marketingofficer of the  Company', 'Active', NOW(), NOW()),
('Company Secretary', 'is-company-secretary','Given to secretary of the  Company', 'Active', NOW(), NOW()),
('Company Loanofficer', 'is-company-loanofficer','Given to loanofficer of the  Company', 'Active', NOW(), NOW()),
('Company client', 'is-company-client','Given to client of the  Company', 'Active', NOW(), NOW()),
('Company user', 'is-company-user','Given to user of the  Company', 'Active', NOW(), NOW());

ALTER TABLE permission_users
ADD COLUMN created_at TIMESTAMP NULL,
ADD COLUMN updated_at TIMESTAMP NULL;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Configuration Side', 'view-confirguration-side','View Configuration Side', 'Active', NOW(), NOW()),
('View Working Side', 'view-working-side','View Working Side', 'Active', NOW(), NOW()),
('View Reporting Side', 'view-reporting-side','View Reporting Side', 'Active', NOW(), NOW());

