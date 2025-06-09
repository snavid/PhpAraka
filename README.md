# PHP Todo List Application

A simple PHP Todo List application with MySQL database integration.

## Features

- Add new tasks with descriptions
- View all tasks with creation dates
- Mark tasks as completed
- Edit existing tasks
- Delete tasks

## Setup Instructions

1. Make sure you have XAMPP installed and running (Apache and MySQL services)
2. Create the database by importing `todo_db.sql` into phpMyAdmin or running:
   ```sql
   mysql -u root -p < todo_db.sql
   ```
3. Place all files in your XAMPP htdocs directory (or a subdirectory)
4. Access the application through your browser:
   ```
   http://localhost/PhpAraka/
   ```
   (Adjust the URL based on your directory structure)

## File Structure

- `index.php` - Main page that shows the task list and add task form
- `functions.php` - Contains all database operations functions
- `config.php` - Database connection configuration
- `edit_task.php` - Edit task details
- `complete_task.php` - Mark a task as completed
- `delete_task.php` - Delete a task
- `style.css` - Styling for the application
- `todo_db.sql` - Database structure

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web server (Apache recommended) 