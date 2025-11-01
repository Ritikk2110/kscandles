<?php
require_once __DIR__ . '/includes/header.php';
$order_id = (int)($_GET['order_id'] ?? 0);
?>
<h1>Thank you!</h1>
<p>Your order <?= $order_id ? "#$order_id" : "" ?> has been received. We emailed the details.</p>
<a href="/kscandles/shop.php" class="btn">Continue Shopping</a>
<?php require __DIR__ . '/includes/footer.php'; ?>
