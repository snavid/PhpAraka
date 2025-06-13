-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Modify tasks table to include user_id
ALTER TABLE tasks ADD COLUMN user_id INT;
ALTER TABLE tasks ADD FOREIGN KEY (user_id) REFERENCES users(id); 