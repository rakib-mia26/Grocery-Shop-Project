<?php
session_start();
include('db.php');

// Destroy the session in the session table
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $session_id = session_id();
    
    // Remove the session from the sessions table
    $sql = "DELETE FROM sessions WHERE session_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $session_id, $user_id);
    $stmt->execute();
    
    // Destroy the session in PHP
    session_unset();
    session_destroy();
}

// Redirect to the home page
header('Location: index.php');
exit();
