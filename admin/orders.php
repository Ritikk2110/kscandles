<?php
// admin/orders.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle order update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = trim($_POST['order_status'] ?? '');
    $new_payment = trim($_POST['payment_status'] ?? '');

    if ($order_id > 0) {
        $stmt = $conn->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_status, $new_payment, $order_id);
        if ($stmt->execute()) {
            $_SESSION['msg'] = "✅ Order #{$order_id} updated successfully.";
        } else {
            $_SESSION['msg'] = "❌ Failed to update order: " . $conn->error;
        }
        header('Location: orders.php');
        exit;
    }
}

// ✅ Fetch all orders with user info
$sql = "SELECT o.*, 
               u.name AS user_name, 
               u.email AS user_email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC";
$ordersRes = $conn->query($sql);

// ✅ Helper to get items with image
function fetch_order_items($conn, $order_id) {
    $stmt = $conn->prepare("SELECT oi.*, p.name AS product_name, p.image AS product_image
                            FROM order_items oi
                            LEFT JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $items = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $items;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Orders - Admin Panel</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
body {font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#f9f7f4,#f1ede8);margin:0;padding:0;color:#4b3f2f;}
.admin-header{background:linear-gradient(135deg,#C4B5A0,#8B7355);padding:18px 40px;display:flex;justify-content:center;flex-wrap:wrap;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
.admin-header .btn{color:#fff;text-decoration:none;padding:10px 18px;margin:6px;border-radius:10px;font-weight:500;background:rgba(255,255,255,0.15);transition:.3s;}
.admin-header .btn:hover,.admin-header .btn.active{background:linear-gradient(135deg,#b68d50,#8B7355);box-shadow:0 4px 12px rgba(182,141,80,0.4);}
main{padding:40px 5%;}
h2{text-align:center;color:#8B7355;margin-bottom:20px;}
.alert.success{background:#f7f2e9;color:#4b3f2f;padding:10px 20px;border-radius:8px;margin-bottom:20px;text-align:center;font-weight:500;border:1px solid #e6dccd;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,0.08);}
th,td{padding:14px 12px;text-align:left;}
thead{background:linear-gradient(135deg,#C4B5A0,#8B7355);color:#fff;font-weight:500;}
tbody tr:nth-child(even){background:#faf8f6;}
tbody tr:hover{background:#f2ede7;transition:.2s;}
button{background:linear-gradient(135deg,#b68d50,#8B7355);color:#fff;border:none;padding:6px 12px;border-radius:6px;cursor:pointer;transition:.3s;font-size:14px;}
button:hover{background:linear-gradient(135deg,#8B7355,#b68d50);transform:scale(1.05);}
select{border:1px solid #d2c5b4;border-radius:8px;padding:6px 10px;background:#fff;color:#4b3f2f;}
.inline-form{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-top:8px;}
.order-items{background:#f9f6f2;border-radius:10px;box-shadow:0 3px 8px rgba(0,0,0,0.05);padding:16px;margin-top:8px;}
.payment-proof{margin-top:10px;background:#fff;padding:12px;border-radius:10px;box-shadow:0 3px 8px rgba(0,0,0,0.05);}
.payment-proof img{max-width:220px;border-radius:8px;display:block;margin-top:8px;}
.address-box{background:#fff8ef;border:1px solid #e5d5c3;border-radius:10px;padding:15px;margin-top:12px;line-height:1.6;}
.address-box strong{color:#8B7355;}
.small-muted{font-size:13px;color:#7d7262;}
.product-img-thumb{width:60px;height:60px;object-fit:cover;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,0.15);}
@media(max-width:768px){table{font-size:14px;}th,td{padding:10px;}.inline-form{flex-direction:column;align-items:stretch;}.inline-form select,.inline-form button{width:100%;}}
  </style>
</head>
<body>

<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<header class="admin-header">
  <a href="index.php" class="btn <?= $current_page == 'index.php' ? 'active' : '' ?>">Dashboard</a>
  <a href="products.php" class="btn <?= $current_page == 'products.php' ? 'active' : '' ?>">Products</a>
  <a href="users.php" class="btn <?= $current_page == 'users.php' ? 'active' : '' ?>">Users</a>
  <a href="orders.php" class="btn <?= $current_page == 'orders.php' ? 'active' : '' ?>">Orders</a>
  <a href="logout.php" class="btn">Logout</a>
</header>

<main>
<?php if(isset($_SESSION['msg'])): ?>
  <div class="alert success"><?= htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
<?php endif; ?>

<h2>Orders Management</h2>

<table>
  <thead>
    <tr>
      <th>Order#</th><th>User</th><th>Total</th><th>Payment</th><th>Status</th><th>Placed At</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while($order = $ordersRes->fetch_assoc()): 
        $items = fetch_order_items($conn, $order['id']); ?>
      <tr>
        <td>#<?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?><br>
          <span class="small-muted"><?= htmlspecialchars($order['user_email'] ?? '') ?></span></td>
        <td>₹ <?= number_format((float)$order['total'], 2) ?></td>
        <td><?= htmlspecialchars($order['payment_method'] ?? '—') ?><br>
            <span class="small-muted"><?= htmlspecialchars($order['payment_status'] ?? 'Pending') ?></span></td>
        <td><?= htmlspecialchars($order['status'] ?? 'Pending') ?></td>
        <td><?= htmlspecialchars($order['created_at'] ?? '') ?></td>
        <td>
          <button onclick="toggleItems('items-<?= $order['id'] ?>')">View</button>
          <form class="inline-form" method="post">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="order_status">
              <?php foreach(['Pending','Processing','Shipped','Delivered','Cancelled','Returned'] as $s): ?>
                <option value="<?= $s ?>" <?= ($order['status']==$s)?'selected':'' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
            <select name="payment_status">
              <?php foreach(['Pending','Paid','Failed','Refunded'] as $p): ?>
                <option value="<?= $p ?>" <?= ($order['payment_status']==$p)?'selected':'' ?>><?= $p ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit">Update</button>
          </form>
        </td>
      </tr>

      <tr id="items-<?= $order['id'] ?>" style="display:none;">
        <td colspan="7">
          <div class="order-items">
            <strong>Items (<?= count($items) ?>)</strong>
            <table style="width:100%;margin-top:8px;border-collapse:collapse;">
              <thead><tr style="background:#fafafa;"><th>Image</th><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
              <tbody>
                <?php foreach($items as $it): ?>
                  <tr>
                    <td>
                      <?php if(!empty($it['product_image'])): ?>
                        <img src="../uploads/products/<?= htmlspecialchars($it['product_image']) ?>" class="product-img-thumb" alt="Product">
                      <?php else: ?>
                        <span class="small-muted">No Image</span>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($it['product_name'] ?? 'Product #'.$it['product_id']) ?></td>
                    <td><?= (int)$it['quantity'] ?></td>
                    <td>₹ <?= number_format((float)$it['price'],2) ?></td>
                    <td>₹ <?= number_format((float)$it['price'] * (int)$it['quantity'],2) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <div class="address-box">
              
              <strong>Shipping Details:</strong><br>
              <b>Name:</b> <?= htmlspecialchars($order['name'] ?? '—') ?><br>
              <b>Address:</b> <?= nl2br(htmlspecialchars($order['address'] ?? '—')) ?><br> 
              <b>City:</b> <?= htmlspecialchars($order['city'] ?? '—') ?><br> 
              <b>State:</b> <?= htmlspecialchars($order['state'] ?? '—') ?><br> 
              <b>Pincode:</b> <?= htmlspecialchars($order['pincode'] ?? '—') ?><br>
              <b>Country:</b> <?= htmlspecialchars($order['country'] ?? 'India') ?>
            </div>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</main>

<script>
function toggleItems(id) {
  const row = document.getElementById(id);
  if (row) row.style.display = (row.style.display === 'none' || row.style.display === '') ? 'table-row' : 'none';
}
</script>

</body>
</html>
