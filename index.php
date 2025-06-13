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

// Get all tasks for the current user
$tasks = getTasks($mysqli, $userId);

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $taskName = trim($_POST['task_name']);
    $description = trim($_POST['description']);
    
    if (!empty($taskName)) {
        addTask($mysqli, $userId, $taskName, $description);
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
    <title>PHP Todo List</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Todo List</h1>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        
        <!-- Add Task Form -->
        <div class="add-task-form">
            <h2>Add New Task</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="task_name">Task Name:</label>
                    <input type="text" id="task_name" name="task_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" name="add_task">Add Task</button>
            </form>
        </div>
        
        <!-- Task List -->
        <div class="task-list">
            <h2>Current Tasks</h2>
            <?php if (count($tasks) > 0): ?>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item <?php echo $task['status'] === 'completed' ? 'completed' : ''; ?>">
                            <div class="task-info">
                                <h3><?php echo htmlspecialchars($task['task_name']); ?></h3>
                                <p><?php echo htmlspecialchars($task['description']); ?></p>
                                <span class="created-at">Created: <?php echo date('M d, Y H:i', strtotime($task['created_at'])); ?></span>
                            </div>
                            <div class="task-actions">
                                <?php if ($task['status'] === 'pending'): ?>
                                    <a href="complete_task.php?id=<?php echo $task['id']; ?>" class="btn-complete">Complete</a>
                                <?php endif; ?>
                                <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-tasks">No tasks yet. Add your first task above!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 