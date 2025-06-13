# PHP Todo List Application

A full-featured Todo List application built with PHP and MySQL, featuring user authentication and task management capabilities.

## Features

- 🔐 User Authentication
  - User registration and login
  - Secure password handling
  - Session management
  - User-specific task lists

- ✅ Task Management
  - Create new tasks with descriptions
  - Edit existing tasks
  - Delete tasks
  - Mark tasks as complete/incomplete
  - View task creation timestamps

- 🎨 User Interface
  - Clean and responsive design
  - Intuitive task management interface
  - User-friendly forms
  - Status indicators for tasks

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP/WAMP/MAMP (for local development)

## Installation

1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd PhpAraka
   ```

2. Set up the database:
   - Create a new MySQL database
   - Import the database schemas:
     ```bash
     mysql -u your_username -p your_database < todo_db.sql
     mysql -u your_username -p your_database < user_tables.sql
     ```

3. Configure the database connection:
   - Open `config.php`
   - Update the database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'todo_db');
     ```

4. Set up the web server:
   - Place the project in your web server's root directory
   - Ensure the web server has read/write permissions

## Project Structure

```
PhpAraka/
├── config.php           # Database configuration
├── functions.php        # General utility functions
├── user_functions.php   # User-related functions
├── index.php           # Main todo list interface
├── login.php           # User login
├── register.php        # User registration
├── logout.php          # User logout
├── edit_task.php       # Edit task functionality
├── delete_task.php     # Delete task functionality
├── complete_task.php   # Complete task functionality
├── style.css           # Main stylesheet
├── landing.css         # Landing page styles
├── index.html          # Landing page
├── todo_db.sql         # Main database schema
└── user_tables.sql     # User tables schema
```

## Usage

1. **Registration**
   - Visit the registration page
   - Create a new account with username, email, and password

2. **Login**
   - Use your credentials to log in
   - Access your personal todo list

3. **Managing Tasks**
   - Add new tasks using the form
   - Edit tasks by clicking the edit button
   - Delete tasks using the delete button
   - Mark tasks as complete/incomplete

## Security Features

- Password hashing for secure storage
- SQL injection prevention
- XSS protection
- Session-based authentication
- Input validation and sanitization

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tasks Table
```sql
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Future Improvements

- [ ] Add task categories/tags
- [ ] Implement due dates for tasks
- [ ] Add task priority levels
- [ ] Email notifications
- [ ] Mobile app integration
- [ ] Task search functionality
- [ ] Task sorting options
- [ ] Dark mode support

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

Your Name - snavidux.official@gmail.com

Project Link: [https://github.com/snavid/PhpAraka](https://github.com/snavid/PhpAraka)

## Code Explanations

### Database Configuration (config.php)
```php
<?php
// Define database connection constants
define('DB_HOST', 'localhost');     // Database host (usually localhost for local development)
define('DB_USER', 'your_username'); // Database username
define('DB_PASS', 'your_password'); // Database password
define('DB_NAME', 'todo_db');       // Database name

// Create new MySQLi connection object
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if connection was successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error); // Terminate script if connection fails
}
```

### User Authentication (user_functions.php)
```php
/**
 * Register a new user in the database
 * @param mysqli $mysqli Database connection object
 * @param string $username User's chosen username
 * @param string $email User's email address
 * @param string $password User's password (will be hashed)
 * @return bool True if registration successful, false otherwise
 */
function registerUser($mysqli, $username, $email, $password) {
    // Hash the password using PHP's secure hashing algorithm
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    // Bind parameters: s = string, i = integer
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    // Execute the statement and return result
    return $stmt->execute();
}

/**
 * Authenticate a user during login
 * @param mysqli $mysqli Database connection object
 * @param string $username User's username
 * @param string $password User's password
 * @return int|bool User ID if login successful, false otherwise
 */
function loginUser($mysqli, $username, $password) {
    // Prepare SQL statement to find user by username
    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    // Get the result set
    $result = $stmt->get_result();
    
    // If user exists, verify password
    if ($user = $result->fetch_assoc()) {
        // Return user ID if password matches, false otherwise
        return password_verify($password, $user['password']) ? $user['id'] : false;
    }
    return false;
}
```

### Task Management (functions.php)
```php
/**
 * Add a new task to the database
 * @param mysqli $mysqli Database connection object
 * @param int $userId ID of the user creating the task
 * @param string $taskName Name of the task
 * @param string $description Task description
 * @return bool True if task added successfully
 */
function addTask($mysqli, $userId, $taskName, $description) {
    // Prepare SQL statement for task insertion
    $stmt = $mysqli->prepare("INSERT INTO tasks (user_id, task_name, description) VALUES (?, ?, ?)");
    
    // Bind parameters: i = integer, s = string
    $stmt->bind_param("iss", $userId, $taskName, $description);
    
    return $stmt->execute();
}

/**
 * Retrieve all tasks for a specific user
 * @param mysqli $mysqli Database connection object
 * @param int $userId ID of the user
 * @return array Array of tasks with all their details
 */
function getTasks($mysqli, $userId) {
    // Prepare SQL statement to get all tasks for user, ordered by creation date
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Return all results as an associative array
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
```

### Main Interface (index.php)
```php
<?php
// Include necessary files
require_once 'config.php';        // Database configuration
require_once 'functions.php';     // Task management functions
require_once 'user_functions.php'; // User authentication functions

// Check if user is logged in, redirect to login if not
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get current user's ID from session
$userId = getCurrentUserId();

// Retrieve all tasks for the current user
$tasks = getTasks($mysqli, $userId);

// Handle new task submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    // Sanitize input data
    $taskName = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    
    // Only add task if name is not empty
    if (!empty($taskName)) {
        addTask($mysqli, $userId, $taskName, $description);
        // Redirect to prevent form resubmission
        header('Location: index.php');
        exit;
    }
}
```

### Task Actions (complete_task.php, delete_task.php, edit_task.php)
```php
/**
 * Mark a task as completed
 * @param mysqli $mysqli Database connection object
 * @param int $taskId ID of the task to complete
 * @param int $userId ID of the user (for security)
 * @return bool True if task was completed successfully
 */
function completeTask($mysqli, $taskId, $userId) {
    // Update task status and set completion timestamp
    $stmt = $mysqli->prepare("UPDATE tasks SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $taskId, $userId);
    return $stmt->execute();
}

/**
 * Delete a task from the database
 * @param mysqli $mysqli Database connection object
 * @param int $taskId ID of the task to delete
 * @param int $userId ID of the user (for security)
 * @return bool True if task was deleted successfully
 */
function deleteTask($mysqli, $taskId, $userId) {
    // Delete task only if it belongs to the user
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $taskId, $userId);
    return $stmt->execute();
}

/**
 * Update an existing task
 * @param mysqli $mysqli Database connection object
 * @param int $taskId ID of the task to update
 * @param int $userId ID of the user (for security)
 * @param string $taskName New task name
 * @param string $description New task description
 * @return bool True if task was updated successfully
 */
function updateTask($mysqli, $taskId, $userId, $taskName, $description) {
    // Update task details only if it belongs to the user
    $stmt = $mysqli->prepare("UPDATE tasks SET task_name = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $taskName, $description, $taskId, $userId);
    return $stmt->execute();
}
```

### Security Features
1. **Password Hashing**
   ```php
   // Hash password using PHP's secure algorithm
   password_hash($password, PASSWORD_DEFAULT)
   
   // Verify password against stored hash
   password_verify($password, $storedHash)
   ```

2. **SQL Injection Prevention**
   ```php
   // Prepare statement to prevent SQL injection
   $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
   // Bind parameter as string
   $stmt->bind_param("s", $username);
   ```

3. **XSS Protection**
   ```php
   // Escape user input to prevent XSS attacks
   // ENT_QUOTES: Convert both single and double quotes
   // UTF-8: Use UTF-8 character encoding
   htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8')
   ```

4. **Session Security**
   ```php
   // Start a new session
   session_start();
   // Regenerate session ID to prevent session fixation
   session_regenerate_id(true);
   ```

## Future Improvements

- [ ] Add task categories/tags
- [ ] Implement due dates for tasks
- [ ] Add task priority levels
- [ ] Email notifications
- [ ] Mobile app integration
- [ ] Task search functionality
- [ ] Task sorting options
- [ ] Dark mode support

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

Your Name - snavidux.official@gmail.com

Project Link: [https://github.com/snavid/PhpAraka](https://github.com/snavid/PhpAraka) 