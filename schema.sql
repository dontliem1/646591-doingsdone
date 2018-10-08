CREATE DATABASE doingsdone;
USE doingsdone;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
date TIMESTAMP,
email VARCHAR(64) UNIQUE,
name VARCHAR(128),
password VARCHAR(128),
contacts VARCHAR(128)
);

CREATE TABLE projects (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(128),
user_id INT
);

CREATE TABLE tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
date_created TIMESTAMP,
date_done TIMESTAMP,
status BOOL,
name VARCHAR(128),
file_path VARCHAR(128) DEFAULT NULL,
deadline DATE DEFAULT NULL,
user_id INT,
project_id INT
);
CREATE INDEX task_name ON tasks(name);