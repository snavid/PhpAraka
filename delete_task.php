<?php
require_once 'config.php';
require_once 'functions.php';

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $taskId = $_GET['id'];
    
    // Delete the task
    deleteTask($mysqli, $taskId);
}

// Redirect back to index page
header('Location: index.php');
exit; 