<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php';
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$razorpay_order_id = $data['order_id'] ?? null;
$razorpay_payment_id = $data['razorpay_payment_id'] ?? null;

if (!$razorpay_payment_id) { echo json_encode(['error'=>'no_payment']); exit; }

$conn->begin_transaction();
try {
    $total = cartTotal($conn);
    $uid = $_SESSION['user_id'];
    $name = $_SESSION['user_name'] ?? '';
    $email = $_SESSION['user_email'] ?? '';
    $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, address, total, order_date, status, payment_method, payment_id) VALUES (?, ?, ?, ?, ?, NOW(), 'processing', 'razorpay', ?)");
    $address = '';
    $stmt->bind_param("isssds", $uid, $name, $email, $address, $total, $razorpay_payment_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    foreach ($_SESSION['cart'] as $pid => $qty) {
        $pstmt = $conn->prepare("SELECT id,name,price,stock FROM products WHERE id = ? FOR UPDATE");
        $pstmt->bind_param("i",$pid); $pstmt->execute();
        $prow = $pstmt->get_result()->fetch_assoc(); $pstmt->close();
        if (!$prow || $prow['stock'] < $qty) { $conn->rollback(); throw new Exception("Stock problem"); }
        $conn->query("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES ($order_id, $pid, '".$conn->real_escape_string($prow['name'])."', ".$prow['price'].", $qty)");
        $upd = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?"); $upd->bind_param("ii",$qty,$pid); $upd->execute(); $upd->close();
    }
    $conn->commit();
    $_SESSION['cart'] = [];
    echo json_encode(['ok'=>true,'order_id'=>$order_id]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['error'=>$e->getMessage()]);
}
