-- Create database
DROP DATABASE IF EXISTS gymmanagement;
CREATE DATABASE gymmanagement;
USE gymmanagement;

-- User base table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    avatar VARCHAR(255),
    phone VARCHAR(20) UNIQUE,
    role ENUM('ADMIN', 'TRAINER', 'MEMBER') DEFAULT 'MEMBER',
    status ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trainers table
CREATE TABLE trainers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    specialization TEXT,
    experience INT,
    certification TEXT,
    salary DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Membership packages
CREATE TABLE packages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT NOT NULL, -- Days
    price DECIMAL(10,2) NOT NULL,
    status ENUM('ACTIVE', 'INACTIVE') DEFAULT 'ACTIVE'
);

-- Member subscriptions
CREATE TABLE memberships (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    package_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('ACTIVE', 'EXPIRED', 'CANCELLED') DEFAULT 'ACTIVE',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (package_id) REFERENCES packages(id)
);

-- Equipment inventory
CREATE TABLE equipment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    quantity INT NOT NULL,
    status ENUM('AVAILABLE', 'MAINTENANCE', 'RETIRED') DEFAULT 'AVAILABLE',
    purchase_date DATE,
    last_maintenance DATE
);

-- Training schedules
CREATE TABLE schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    trainer_id INT NOT NULL,
    member_id INT NOT NULL,
    schedule_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('SCHEDULED', 'COMPLETED', 'CANCELLED') DEFAULT 'SCHEDULED',
    FOREIGN KEY (trainer_id) REFERENCES trainers(id),
    FOREIGN KEY (member_id) REFERENCES users(id)
);

-- Payments
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    membership_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method ENUM('CASH', 'TRANSFER', 'CARD'),
    status ENUM('PENDING', 'COMPLETED', 'FAILED') DEFAULT 'PENDING',
    FOREIGN KEY (membership_id) REFERENCES memberships(id)
);

-- File uploads
CREATE TABLE files (
    id INT PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    filetype VARCHAR(50) NOT NULL,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Add indexes
CREATE INDEX idx_user_role ON users(role);
CREATE INDEX idx_membership_dates ON memberships(start_date, end_date);
CREATE INDEX idx_schedule_date ON schedules(schedule_date);
CREATE INDEX idx_payment_status ON payments(status);

-- Add default admin account
INSERT INTO users (username, password, email, fullname, role) VALUES
('admin', '$2y$10$CCBot307rkLgGsKS1JrIWOlrU9J7BFxrEc5I7XmpibqDIOS/.eKJa', 'admin@gym.com', 'Administrator', 'ADMIN');

-- Create revenue view
CREATE VIEW revenue_by_package AS
SELECT 
    p.name as package_name,
    COUNT(m.id) as total_subscriptions,
    SUM(py.amount) as total_revenue
FROM packages p
LEFT JOIN memberships m ON p.id = m.package_id
LEFT JOIN payments py ON m.id = py.membership_id
WHERE py.status = 'COMPLETED'
GROUP BY p.id;