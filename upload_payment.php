<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $utr = trim($_POST['utr']);
    $file = $_FILES['payment_screenshot'];

    if ($order_id && $utr && $file['tmp_name']) {
        $target_dir = "uploads/payments/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = "payment_" . $order_id . "_" . time() . ".jpg";
        move_uploaded_file($file['tmp_name'], $target_dir . $filename);

        $stmt = $conn->prepare("UPDATE orders SET payment_proof=?, utr=?, payment_status='pending_review' WHERE id=?");
        $stmt->bind_param("ssi", $filename, $utr, $order_id);
        $stmt->execute();

        echo "<script>alert('Payment submitted for review!');window.location='thankyou.php?order_id=$order_id';</script>";
    } else {
        echo "<script>alert('Please fill all fields correctly.');history.back();</script>";
    }
}
?>
