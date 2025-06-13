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
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $taskId = $_GET['id'];
    
    // Mark task as completed
    completeTask($mysqli, $userId, $taskId);
}

// Redirect back to index page
header('Location: index.php');
exit; 