<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$totalProducts = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$totalOrders = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$totalRevenue = $conn->query("SELECT IFNULL(SUM(total),0) AS s FROM orders")->fetch_assoc()['s'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard | KSCandles</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* ==============================
   KSCANDLES UNIFIED ADMIN THEME
   ============================== */
:root {
  --bg-light: #faf6f1;
  --bg-card: #fffdf9;
  --primary: #a17433;
  --accent: #c39a58;
  --text-dark: #3e2b18;
  --text-light: #6b5b46;
  --shadow: rgba(0, 0, 0, 0.08);
  --radius: 14px;
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--bg-light);
  color: var(--text-dark);
  margin: 0;
  padding: 0;
}

/* Header (Glass Effect) */
header {
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 0.7);
  border-bottom: 1px solid rgba(255,255,255,0.4);
  position: sticky;
  top: 0;
  z-index: 100;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 40px;
  box-shadow: 0 2px 15px var(--shadow);
}

header h1 {
  font-size: 24px;
  font-weight: 600;
  color: var(--primary);
  margin: 0;
  letter-spacing: 1px;
}

header nav a {
  color: var(--text-dark);
  text-decoration: none;
  margin: 0 10px;
  padding: 8px 14px;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

header nav a:hover {
  background: rgba(193, 154, 88, 0.15);
  color: var(--primary);
}

/* Main Section */
main {
  padding: 40px 30px;
  max-width: 1200px;
  margin: 0 auto;
}

/* Dashboard Cards */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 25px;
  margin-bottom: 50px;
}

.card {
  background: var(--bg-card);
  border-radius: var(--radius);
  box-shadow: 0 4px 15px var(--shadow);
  text-align: center;
  padding: 28px 20px;
  position: relative;
  transition: all 0.3s ease;
  border: 1px solid rgba(193,154,88,0.15);
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.card h3 {
  font-size: 1rem;
  color: var(--accent);
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card p {
  font-size: 1.6rem;
  font-weight: 600;
  color: var(--text-dark);
  margin: 0;
}

/* Chart Container */
h3 {
  text-align: center;
  color: var(--primary);
  font-size: 1.2rem;
  margin-bottom: 15px;
}

canvas {
  background: #fff;
  padding: 20px;
  border-radius: var(--radius);
  box-shadow: 0 5px 18px var(--shadow);
  width: 100%;
  max-width: 900px;
  margin: 0 auto;
  display: block;
}

/* Footer */
footer {
  text-align: center;
  padding: 20px;
  font-size: 14px;
  color: var(--text-light);
  margin-top: 40px;
  border-top: 1px solid rgba(0,0,0,0.05);
  background: rgba(255,255,255,0.7);
}

/* Responsive */
@media (max-width: 768px) {
  header {
    flex-direction: column;
    gap: 10px;
    text-align: center;
  }

  header nav a {
    display: inline-block;
    margin: 5px;
  }
}
</style>
</head>

<body>
<header>
  <h1>KSCandles Admin</h1>
  <nav>
    <a href="products.php">Products</a>
    <a href="orders.php">Orders</a>
    <a href="users.php">Users</a>
    <a href="messages.php">Messages</a>
    <a href="reviews.php">Reviews</a>
    <a href="qr_settings.php">QR Settings</a>
    <a href="abandoned_carts.php">Tracking</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<main>
  <div class="cards">
    <div class="card"><h3>Users</h3><p><?= $totalUsers ?></p></div>
    <div class="card"><h3>Products</h3><p><?= $totalProducts ?></p></div>
    <div class="card"><h3>Orders</h3><p><?= $totalOrders ?></p></div>
    <div class="card"><h3>Revenue</h3><p>₹ <?= number_format($totalRevenue,2) ?></p></div>
  </div>

  <h3>Sales (Last 12 Months)</h3>
  <canvas id="salesChart"></canvas>

  <script>
    fetch('sales_data.php')
      .then(r => r.json())
      .then(data => {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Revenue',
              data: data.values,
              borderColor: '#a17433',
              backgroundColor: 'rgba(193,154,88,0.1)',
              fill: true,
              tension: 0.35
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
              y: { ticks: { color: '#6b5b46' } },
              x: { ticks: { color: '#6b5b46' } }
            }
          }
        });
      });
  </script>
</main>

<footer>
  © <?= date('Y') ?> KSCandles — Admin Panel
</footer>
</body>
</html>
