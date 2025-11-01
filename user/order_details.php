<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['user_id'])) header('Location: /kscandles/login.php');
$uid = $_SESSION['user_id'];
$order_id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1");
$stmt->bind_param("ii", $order_id, $uid); $stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) { echo "Order not found."; exit; }
$items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$items->bind_param("i",$order_id); $items->execute(); $itemsRes = $items->get_result();
require __DIR__ . '/../includes/header.php';
?>
<h1>Order #<?= $order_id ?></h1>
<p>Total: ₹ <?= number_format($order['total'],2) ?></p>
<p>Status: <?= htmlspecialchars($order['status']) ?></p>
<h3>Items</h3>
<table border="1"><thead><tr><th>Product</th><th>Price</th><th>Qty</th></tr></thead>
<tbody>
<?php while($it = $itemsRes->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($it['product_name']) ?></td>
  <td>₹ <?= number_format($it['price'],2) ?></td>
  <td><?= (int)$it['quantity'] ?></td>
</tr>
<?php endwhile; ?>
</tbody></table>
<?php require __DIR__ . '/../includes/footer.php'; ?>
