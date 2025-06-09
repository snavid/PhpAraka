<?php
/**
 * Get all tasks from the database
 */
function getTasks($pdo) {
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Add a new task
 */
function addTask($pdo, $taskName, $description = '') {
    $stmt = $pdo->prepare("INSERT INTO tasks (task_name, description) VALUES (?, ?)");
    return $stmt->execute([$taskName, $description]);
}

/**
 * Get a single task by ID
 */
function getTaskById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update a task
 */
function updateTask($pdo, $id, $taskName, $description) {
    $stmt = $pdo->prepare("UPDATE tasks SET task_name = ?, description = ? WHERE id = ?");
    return $stmt->execute([$taskName, $description, $id]);
}

/**
 * Mark a task as completed
 */
function completeTask($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Delete a task
 */
function deleteTask($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    return $stmt->execute([$id]);
}
?> 