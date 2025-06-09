<?php
require_once 'config.php';
require_once 'functions.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$taskId = $_GET['id'];
$task = getTaskById($pdo, $taskId);

// If task doesn't exist, redirect
if (!$task) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    $taskName = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    
    if (!empty($taskName)) {
        updateTask($pdo, $taskId, $taskName, $description);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - PHP Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Task</h1>
        
        <div class="add-task-form">
            <form method="post" action="">
                <div class="form-group">
                    <label for="task_name">Task Name:</label>
                    <input type="text" id="task_name" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" name="update_task">Update Task</button>
                    <a href="index.php" class="btn-cancel" style="display: inline-block; margin-left: 10px; background-color: #7f8c8d; padding: 10px 20px; color: #fff; text-decoration: none; border-radius: 4px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 