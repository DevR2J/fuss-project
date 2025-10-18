CREATE DATABASE IF NOT EXISTS flinders;
USE flinders;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    degree VARCHAR(100),
    college VARCHAR(50),
    academic_year INT,
    bio TEXT,
    profile_pic VARCHAR(255),
    fussc_balance DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE skills_offered (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    skill_name VARCHAR(200) NOT NULL,
    description TEXT,
    category ENUM('Academic', 'Tech Support', 'Life Skills'),
    topic_degree VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE skills_requested (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    skill_name VARCHAR(200) NOT NULL,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT,
    provider_id INT,
        skill_name VARCHAR(200) NOT NULL,
        hours DECIMAL(3,2),
        credits DECIMAL(3,2),
        type ENUM('Offered', 'Received'),
    service_date DATE,
    FOREIGN KEY (requester_id) REFERENCES users(id),
    FOREIGN KEY (provider_id) REFERENCES users(id)
);
