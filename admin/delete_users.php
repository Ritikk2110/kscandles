<?php
// admin/delete_user.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = "Invalid user id.";
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];

// Optional: Prevent deleting the main admin user or yourself (if you are storing admin in users table)
// Example: if ($id === $_SESSION['admin_user_row_id']) { ... }

// Delete user safely (consider foreign key constraints in your DB — order_items/orders etc.)
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    $_SESSION['msg'] = "User deleted successfully.";
} else {
    $_SESSION['msg'] = "Failed to delete user: " . $conn->error;
}

header('Location: users.php');
exit;
