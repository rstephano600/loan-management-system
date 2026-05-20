-- 20/04/2026

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

-- 04/05/2026
INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Country', 'view-country','View Country', 'Active', NOW(), NOW()),
('Create Country', 'create-country','Create Country', 'Active', NOW(), NOW()),
('Create Read Update Delete Country', 'crud-country','Crud Country', 'Active', NOW(), NOW()),
('View Currency', 'view-currency','View Currency', 'Active', NOW(), NOW()),
('Create Currency', 'create-currency','create Currency', 'Active', NOW(), NOW()),
('Create Read Update Delete Currency', 'crud-currency','Create Read Update Delete Currency Currency', 'Active', NOW(), NOW()),
('View Today Currency', 'view-today-currency','View Today Currency', 'Active', NOW(), NOW()),
('Create Today Currency', 'create-today-currency','create Today Currency', 'Active', NOW(), NOW()),
('Create Read Update Delete Today Currency', 'crud-today-currency','Create Read Update Delete Today Currency Currency', 'Active', NOW(), NOW()),
('View Company Branches', 'view-company-branches','View Company Branches', 'Active', NOW(), NOW()),
('create Company Branches', 'create-company-branches','Create Company Branches', 'Active', NOW(), NOW()),
('Create Read Update Delete Company Branches', 'crud-company-branches','Create Read Update Delete Company Branches', 'Active', NOW(), NOW()),
('View Accounting Codes', 'view-accounting-codes','View The Accounting Codes', 'Active', NOW(), NOW()),
('Create Accounting Codes', 'create-accounting-codes','Create The Accounting Codes', 'Active', NOW(), NOW()),
('Create Read Update Delete Accounting Codes', 'crud-accounting-codes','Create Read Update Delete The Accounting Codes', 'Active', NOW(), NOW());

CREATE TABLE account_countries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CountryCode VARCHAR(10),
    CountryName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE account_businesses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Country_id BIGINT UNSIGNED NOT NULL,
    BusinessCode VARCHAR(50),
    BusinessName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (Country_id) REFERENCES account_countries(id) ON DELETE CASCADE
);

CREATE TABLE account_roots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    AccountCode VARCHAR(50) UNIQUE,
    AccountName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE account_first_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    AccountRoot_id BIGINT UNSIGNED NOT NULL,
    FirstAccountCode VARCHAR(50),
    FirstAccountName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (AccountRoot_id) REFERENCES account_roots(id) ON DELETE CASCADE
);

CREATE TABLE account_second_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FirstRoot_id BIGINT UNSIGNED NOT NULL,
    SecondAccountCode VARCHAR(50),
    SecondAccountName VARCHAR(255),
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (FirstRoot_id) REFERENCES account_first_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_third_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    SecondRoot_id BIGINT UNSIGNED NOT NULL,
    ThirdAccountCode VARCHAR(50),
    ThirdAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Report',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (SecondRoot_id) REFERENCES account_second_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_fourth_center_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ThirdRoot_id BIGINT UNSIGNED NOT NULL,
    FourthAccountCode VARCHAR(50),
    FourthAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ThirdRoot_id) REFERENCES account_third_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_fifth_group_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FourthRoot_id BIGINT UNSIGNED NOT NULL,
    FifthAccountCode VARCHAR(50),
    FifthAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (FourthRoot_id) REFERENCES account_fourth_center_branches(id) ON DELETE CASCADE
);

CREATE TABLE account_sixth_member_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FifthRoot_id BIGINT UNSIGNED NOT NULL,
    SixthAccountCode VARCHAR(50),
    SixthAccountName VARCHAR(255),
    Category VARCHAR(100) NULL,
    User_id BIGINT UNSIGNED NOT NULL,
    Status VARCHAR(50) DEFAULT 'Active',
    AuditingStatus VARCHAR(50) DEFAULT 'Pending',
    ReportStatus VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (User_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (FifthRoot_id) REFERENCES account_fifth_group_branches(id) ON DELETE CASCADE
);

ALTER TABLE users
ADD COLUMN Roles VARCHAR(20) DEFAULT 'User' AFTER role;

UPDATE users
SET Roles = role;

ALTER TABLE users
DROP COLUMN role;

ALTER TABLE users
ADD COLUMN Role VARCHAR(20) DEFAULT 'User' AFTER Roles;

UPDATE users
SET Role = Roles;

ALTER TABLE users
DROP COLUMN Roles;

ALTER TABLE users
ADD COLUMN FirstName VARCHAR(50) NULL AFTER name,
ADD COLUMN MiddleName VARCHAR(50) NULL AFTER FirstName,
ADD COLUMN LastName VARCHAR(50) NULL AFTER MiddleName;

ALTER TABLE users
DROP COLUMN created_by;

ALTER TABLE users
DROP COLUMN updated_by;

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Accounting Menu', 'view-accounting-menu','View Accounting Menu', 'Active', NOW(), NOW());

-- tarehe 16/05/2026

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View Employee Menu', 'view-employee-menu','View Employee Menu', 'Active', NOW(), NOW()),
('Register Employees', 'register-employees','Register Employees', 'Active', NOW(), NOW()),
('Update, Delete Employees', 'crud-employees','Update, Delete Employees', 'Active', NOW(), NOW()),
('View Permission Access Menu', 'view-permission-access-menu','View Permission Access Menu', 'Active', NOW(), NOW()),
('Assign Permission Access', 'assign-permission-access','Assign Permission Access', 'Active', NOW(), NOW()),
('Assign As Admin Permission Access', 'assign-AsAdmin-permission-access','Assign As Admin Permission Access', 'Active', NOW(), NOW()),
('Register Permission Access', 'register-permission-access','Register Permission Access', 'Active', NOW(), NOW());

INSERT INTO permissions (name, slug, description, Status, created_at, updated_at) VALUES
('View System Users Menu', 'view-system-users-menu','View System Users Menu', 'Active', NOW(), NOW()),
('View System Users', 'view-system-users','View System Users', 'Active', NOW(), NOW()),
('Register System Users Menu', 'register-system-users-menu','Register System Users Menu', 'Active', NOW(), NOW()),
('Update, Delete System Users Menu', 'crud-system-users-menu','Update, Delete System Users Menu', 'Active', NOW(), NOW());


ALTER TABLE employees
ADD COLUMN Employee_id BIGINT NOT NULL AFTER id;

ALTER TABLE employees
DROP COLUMN Emp_id;

ALTER TABLE employees
DROP COLUMN first_name,
DROP COLUMN middle_name,
DROP COLUMN last_name,
DROP COLUMN gender,
DROP COLUMN date_of_birth;

ALTER TABLE users
ADD COLUMN gender VARCHAR(20) NULL AFTER Role,
ADD COLUMN Dob VARCHAR(20) NULL AFTER gender;

ALTER TABLE users
DROP COLUMN Emp_id;

ALTER TABLE employees
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER other_information,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE employees
ADD COLUMN EmployeeID BIGINT NOT NULL AFTER id;

ALTER TABLE employees
MODIFY COLUMN EmployeeID VARCHAR(100) NOT NULL;

ALTER TABLE employees
MODIFY COLUMN Employee_id BIGINT UNSIGNED NOT NULL;

UPDATE employees
SET Employee_id = User_id;

ALTER TABLE employees
ADD CONSTRAINT fk_employees_user
FOREIGN KEY (Employee_id)
REFERENCES users(id)
ON DELETE CASCADE;


ALTER TABLE referee
ADD COLUMN User_id BIGINT UNSIGNED NOT NULL AFTER other_informations,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE referee
ADD CONSTRAINT fk_referee_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

--20/07/2026
ALTER TABLE employees
ADD COLUMN basic_salary BIGINT NOT NULL AFTER other_information;

ALTER TABLE referee
ADD COLUMN occupation VARCHAR(50) DEFAULT NULL AFTER other_informations;

ALTER TABLE nist_of_kin
ADD COLUMN relationship VARCHAR(50) DEFAULT NULL AFTER other_informations;

ALTER TABLE nist_of_kin
ADD COLUMN User_id BIGINT UNSIGNED NULL AFTER other_informations,
ADD COLUMN Status VARCHAR(50) DEFAULT 'Active' AFTER User_id,
ADD COLUMN AuditingStatus VARCHAR(50) DEFAULT 'Pending' AFTER Status,
ADD COLUMN ReportStatus VARCHAR(50) DEFAULT 'Pending' AFTER AuditingStatus;

ALTER TABLE nist_of_kin
ADD CONSTRAINT fk_nist_of_kin_user
FOREIGN KEY (User_id)
REFERENCES users(id)
ON DELETE CASCADE;

