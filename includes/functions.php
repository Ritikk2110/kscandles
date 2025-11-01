<?php
// includes/functions.php

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get all products (sorted by ID instead of created_at)
function getProducts($conn) {
    $sql = "SELECT * FROM products ORDER BY id DESC";
    return $conn->query($sql);
}

// Get single product by ID (safe prepared query)
function getProduct($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

// Add to cart
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product_id = (int)$product_id;
    $quantity = max(1, (int)$quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Get cart total
function cartTotal($conn) {
    $total = 0;

    if (empty($_SESSION['cart'])) return 0;

    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $conn->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($r = $res->fetch_assoc()) {
        $id = $r['id'];
        $price = (float)$r['price'];
        $qty = $_SESSION['cart'][$id] ?? 0;
        $total += $price * $qty;
    }

    $stmt->close();
    return $total;
}

// Safe redirect
function redirect($url) {
    header("Location: $url");
    exit;
}
?>
