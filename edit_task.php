<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'user_functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = getCurrentUserId();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$taskId = $_GET['id'];
$task = getTaskById($mysqli, $userId, $taskId);

// If task doesn't exist or doesn't belong to user, redirect
if (!$task) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    $taskName = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    
    if (!empty($taskName)) {
        updateTask($mysqli, $userId, $taskId, $taskName, $description);
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
        <div class="header">
            <h1>Edit Task</h1>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        
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
                    <a href="index.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 