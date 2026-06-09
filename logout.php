<?php
session_start();

// Store the message BEFORE destroying session
$logout_message = "You have been logged out successfully.";

// Clear all session variables
$_SESSION = array();

// Destroy session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Start a fresh session for the logout message
session_start();
session_regenerate_id(true);  // Prevent session fixation
$_SESSION['logout_msg'] = $logout_message;

// Redirect to login
header("Location: login.php");
exit();
?>