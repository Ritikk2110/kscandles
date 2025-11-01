<?php
// orders.php - User order history
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: /kscandles/login.php');
  exit;
}

$uid = (int)$_SESSION['user_id'];
$view_order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function fetch_items($conn, $order_id) {
  $stmt = $conn->prepare("SELECT oi.*, p.name AS product_name, p.image AS product_image 
                          FROM order_items oi 
                          LEFT JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?");
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $res;
}

if ($view_order_id > 0) {
  $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1");
  $stmt->bind_param("ii", $view_order_id, $uid);
  $stmt->execute();
  $order = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$order) {
    $error = "Order not found.";
  } else {
    $items = fetch_items($conn, $view_order_id);
  }
}

$ordersRes = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$ordersRes->bind_param("i", $uid);
$ordersRes->execute();
$orders = $ordersRes->get_result();
$ordersRes->close();
?>



<style>
/* =========================================
   KSCANDLES — MY ORDERS PAGE THEME
   ========================================= */
body {
  /*
  font-family: "Poppins", sans-serif;*/
  background: linear-gradient(135deg, #fbe8d3, #f8d1b0, #f6b26b);
  background-attachment: fixed;
  margin: 0;
  color: #3e2a1d;
}

/* ===== PAGE TITLE ===== */
.page-title {
  text-align: center;
  margin: 50px 0 30px;
  font-size: 2rem;
  color: #3e2a1d;
  letter-spacing: 0.5px;
  position: relative;
}

.page-title::after {
  content: "";
  width: 70px;
  height: 4px;
  background: linear-gradient(135deg, #eab676, #e0853f);
  border-radius: 2px;
  display: block;
  margin: 10px auto 0;
}

/* ===== MAIN LAYOUT ===== */
.orders-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto 80px;
  padding: 0 20px;
}

/* ===== SIDEBAR ===== */
.sidebar {
  background: #fffaf5;
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 6px 25px rgba(180, 115, 70, 0.15);
  height: fit-content;
  transition: all 0.3s ease;
}

.sidebar:hover {
  box-shadow: 0 10px 35px rgba(180, 115, 70, 0.25);
}

.user-card {
  text-align: center;
  border-bottom: 1px solid #f0e4d6;
  padding-bottom: 18px;
  margin-bottom: 25px;
}

.user-icon {
  font-size: 50px;
  background: linear-gradient(135deg, #eab676, #e0853f);
  color: #fff;
  width: 70px;
  height: 70px;
  border-radius: 50%;
  line-height: 70px;
  margin: 0 auto 12px;
  box-shadow: 0 4px 10px rgba(224, 133, 63, 0.3);
}

.user-card h3 {
  margin: 6px 0 2px;
  color: #3e2a1d;
}

.user-card p {
  font-size: 0.9rem;
  color: #7b6757;
}

.sidebar-nav a {
  display: block;
  padding: 10px 15px;
  margin-bottom: 10px;
  text-decoration: none;
  color: #3e2a1d;
  border-radius: 10px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.sidebar-nav a:hover,
.sidebar-nav a.active {
  background: linear-gradient(135deg, #eab676, #e0853f);
  color: #fff;
  box-shadow: 0 3px 10px rgba(224, 133, 63, 0.3);
}

/* ===== MAIN CONTENT ===== */
.orders-content {
  background: #fffaf5;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 6px 25px rgba(180, 115, 70, 0.12);
  transition: all 0.3s ease;
}

.orders-content:hover {
  box-shadow: 0 10px 35px rgba(180, 115, 70, 0.25);
}

/* ===== ORDER TABLE ===== */
.orders-table {
  width: 100%;
  border-collapse: collapse;
  overflow: hidden;
  border-radius: 12px;
}

.orders-table th,
.orders-table td {
  padding: 14px 18px;
  border-bottom: 1px solid #f0e4d6;
  text-align: left;
}

.orders-table th {
  background: #fff0e0;
  font-weight: 600;
  color: #3e2a1d;
}

.orders-table tr:hover {
  background: #fdf5ec;
  transition: 0.3s;
}

/* ===== STATUS BADGES ===== */
.status {
  padding: 6px 10px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status.pending {
  background: #fff6d6;
  color: #a67c00;
}
.status.completed {
  background: #e9f9ee;
  color: #2a7b3f;
}
.status.cancelled {
  background: #fdecec;
  color: #b13030;
}

/* ===== ORDER DETAIL VIEW ===== */
.order-detail {
  padding: 20px;
}

.order-header {
  background: #fffaf3;
  border: 1px solid #f4e3c0;
  padding: 20px;
  border-radius: 15px;
  margin-bottom: 25px;
}

.order-header h2 {
  font-size: 1.3rem;
  margin-bottom: 10px;
  color: #3e2a1d;
}

.order-header p {
  color: #6b4e3d;
  margin: 6px 0;
}

.item-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.item-card {
  background: #fff;
  border: 1px solid #f0e4d6;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(180, 115, 70, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.item-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(180, 115, 70, 0.2);
}

.item-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.item-info {
  padding: 14px;
}

.item-info h4 {
  font-size: 1rem;
  margin: 0 0 8px;
  color: #3e2a1d;
}

.item-info p {
  margin: 4px 0;
  color: #6b4e3d;
}

/* ===== BUTTONS ===== */
.btn {
  background: linear-gradient(135deg, #eab676, #e0853f);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 9px 16px;
  text-decoration: none;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(135deg, #e0853f, #d66d0a);
  box-shadow: 0 4px 15px rgba(224, 133, 63, 0.35);
  transform: translateY(-2px);
}

.btn.small {
  font-size: 0.85rem;
  padding: 6px 10px;
}

.btn.back {
  display: inline-block;
  margin-top: 25px;
}

/* ===== EMPTY STATE ===== */
.no-orders {
  text-align: center;
  color: #7b6757;
  font-size: 1rem;
  font-style: italic;
  margin: 30px 0;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
  .orders-layout {
    grid-template-columns: 1fr;
  }

  .sidebar {
    margin-bottom: 25px;
  }

  .orders-content {
    padding: 20px;
  }
}

</style>

<h1 class="page-title">My Orders</h1>

<div class="orders-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="user-card">
      <div class="user-icon">👤</div>
      <h3><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h3>
      <p><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
    </div>
    <nav class="sidebar-nav">
      <a href="/kscandles/profile.php">👤 Profile</a>
      <a href="/kscandles/orders.php" class="active">📦 My Orders</a>
      <a href="/kscandles/cart.php">🛒 My Cart</a>
      <a href="/kscandles/logout.php">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <section class="orders-content">
    <?php if (!empty($error)): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($view_order_id > 0 && isset($order)): ?>
      <div class="order-detail">
        <div class="order-header">
          <h2>Order #<?= $order['id'] ?> <span class="status <?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status'] ?? 'Pending') ?></span></h2>
          <p><strong>Placed:</strong> <?= htmlspecialchars($order['created_at'] ?? '') ?></p>
          <p><strong>Total:</strong> ₹ <?= number_format((float)$order['total'], 2) ?></p>
          <p><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method'] ?? '—') ?> — <?= htmlspecialchars($order['payment_status'] ?? '') ?></p>
        </div>

        <div class="order-items">
          <h3>Items in this order</h3>
          <div class="item-grid">
            <?php foreach ($items as $it): ?>
              <div class="item-card">
                <img src="/kscandles/<?= htmlspecialchars($it['product_image'] ?? 'assets/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($it['product_name']) ?>">
                <div class="item-info">
                  <h4><?= htmlspecialchars($it['product_name']) ?></h4>
                  <p>Qty: <?= (int)$it['quantity'] ?></p>
                  <p>Price: ₹<?= number_format((float)$it['price'], 2) ?></p>
                  <p><strong>Subtotal:</strong> ₹<?= number_format((float)$it['price'] * (int)$it['quantity'], 2) ?></strong></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <a class="btn back" href="/kscandles/orders.php">← Back to all orders</a>
      </div>

    <?php else: ?>
      <div class="orders-table-container">
        <?php if ($orders->num_rows === 0): ?>
          <p class="no-orders">You haven’t placed any orders yet.</p>
        <?php else: ?>
          <table class="orders-table">
            <thead>
              <tr><th>Order #</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
              <?php while ($o = $orders->fetch_assoc()): ?>
                <tr>
                  <td>#<?= $o['id'] ?></td>
                  <td><?= htmlspecialchars($o['created_at'] ?? '') ?></td>
                  <td>₹ <?= number_format((float)$o['total'], 2) ?></td>
                  <td><?= htmlspecialchars($o['payment_status'] ?? 'Pending') ?></td>
                  <td><span class="status <?= strtolower($o['status']) ?>"><?= htmlspecialchars($o['status'] ?? 'Pending') ?></span></td>
                  <td><a class="btn small" href="/kscandles/orders.php?id=<?= $o['id'] ?>">View</a></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>



