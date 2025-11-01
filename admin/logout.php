<?php
// admin/logout.php
session_start();

// Unset all admin-related session variables (be conservative)
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_name']); // if you used this

// Optionally clear entire session (uncomment if you want)
$_SESSION = [];

// Destroy session cookie (if set)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally destroy the session
session_destroy();

// Redirect to admin login
header('Location: login.php');
exit;
