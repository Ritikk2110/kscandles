<?php
// admin/delete_product.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete product image if exists
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result && !empty($result['image'])) {
        $imagePath = __DIR__ . '/../uploads/' . $result['image'];
        if (file_exists($imagePath)) unlink($imagePath);
    }
    $stmt->close();

    // Delete product record
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Product deleted successfully!";
}

header('Location: products.php');
exit;
?>
