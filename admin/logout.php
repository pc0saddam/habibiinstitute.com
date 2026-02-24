<?php
// admin/logout.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Log the logout activity
if(isset($_SESSION['admin_username'])) {
    logAdminActivity('LOGOUT', 'User logged out from IP: ' . getUserIP());
}

// Clear remember me cookie if set
if(isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Destroy all session data
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Redirect to login page with message
header('Location: login.php?loggedout=1');
exit();
?>