<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// ✅ Save user's cart before logout (manual or idle)
if (isset($_SESSION['user_id']) && !empty($_SESSION['cart'])) {
    $user_id = (int)$_SESSION['user_id'];
    $cartData = json_encode($_SESSION['cart']);

    // Delete any existing abandoned cart for this user
    $deleteStmt = $conn->prepare("DELETE FROM abandoned_carts WHERE user_id = ?");
    $deleteStmt->bind_param("i", $user_id);
    $deleteStmt->execute();

    // Insert the latest cart data
    $stmt = $conn->prepare("INSERT INTO abandoned_carts (user_id, cart_data) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $cartData);
    $stmt->execute();
}

// ✅ Clear session
session_unset();
session_destroy();

// ✅ Redirect user
if (isset($_GET['timeout'])) {
    // If logout caused by inactivity
    header("Location: /kscandles/index.php?message=Session expired due to inactivity");
} else {
    // Manual logout
    header("Location: /kscandles/index.php?message=Logged out successfully");
}
exit;
?>
