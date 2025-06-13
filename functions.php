<?php
/**
 * Get all tasks from the database
 */
function getTasks($mysqli) {
    $result = $mysqli->query("SELECT * FROM tasks ORDER BY created_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Add a new task
 */
function addTask($mysqli, $taskName, $description = '') {
    $stmt = $mysqli->prepare("INSERT INTO tasks (task_name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $taskName, $description);
    return $stmt->execute();
}

/**
 * Get a single task by ID
 */
function getTaskById($mysqli, $id) {
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Update a task
 */
function updateTask($mysqli, $id, $taskName, $description) {
    $stmt = $mysqli->prepare("UPDATE tasks SET task_name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $taskName, $description, $id);
    return $stmt->execute();
}

/**
 * Mark a task as completed
 */
function completeTask($mysqli, $id) {
    $stmt = $mysqli->prepare("UPDATE tasks SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * Delete a task
 */
function deleteTask($mysqli, $id) {
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?> 