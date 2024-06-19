<?php
// Initialize the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page after logout
header("Location: login.php");
exit;
?>
