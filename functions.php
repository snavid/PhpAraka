<?php
/**
 * Get all tasks from the database for a specific user
 */
function getTasks($mysqli, $userId) {
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Add a new task
 */
function addTask($mysqli, $userId, $taskName, $description = '') {
    $stmt = $mysqli->prepare("INSERT INTO tasks (user_id, task_name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $taskName, $description);
    return $stmt->execute();
}

/**
 * Get a single task by ID
 */
function getTaskById($mysqli, $userId, $id) {
    $stmt = $mysqli->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Update a task
 */
function updateTask($mysqli, $userId, $id, $taskName, $description) {
    $stmt = $mysqli->prepare("UPDATE tasks SET task_name = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $taskName, $description, $id, $userId);
    return $stmt->execute();
}

/**
 * Mark a task as completed
 */
function completeTask($mysqli, $userId, $id) {
    $stmt = $mysqli->prepare("UPDATE tasks SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    return $stmt->execute();
}


// function UnMarkcompleteTask($mysqli, $userId, $id) {
//     $stmt = $mysqli->prepare("UPDATE tasks SET status = 'incomplete', completed_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
//     $stmt->bind_param("ii", $id, $userId);
//     return $stmt->execute();
// }

/**
 * Delete a task
 */
function deleteTask($mysqli, $userId, $id) {
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    return $stmt->execute();
}
?> 