CREATE DATABASE online_voting;

USE online_voting;

-- Voters table
CREATE TABLE voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    has_voted TINYINT(1) DEFAULT 0
);

-- Candidates table
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    party VARCHAR(100) NOT NULL,
    votes INT DEFAULT 0
);

-- Admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Demo admin
INSERT INTO admin (username, password)
VALUES ('admin', SHA2('admin123', 256));

-- Demo candidates
INSERT INTO candidates (name, party)
VALUES
('Rahul Sharma', 'People Party'),
('Priya Patel', 'Development Party'),
('Amit Singh', 'National Party');