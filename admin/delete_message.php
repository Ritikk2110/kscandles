<?php
// admin/delete_message.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $token = $_POST['csrf_token'] ?? '';

    // Verify CSRF token
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.');
    }

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            unset($_SESSION['csrf_token']);
            // ✅ Corrected redirect path
            header('Location: messages.php?msg=deleted');
            exit;
        } else {
            die('Error deleting message.');
        }
    } else {
        die('Invalid message ID.');
    }
} else {
    header('Location: message.php');
    exit;
}
?>
