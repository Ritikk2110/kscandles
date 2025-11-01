<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// ✅ Fetch products with category names
$sql = "
    SELECT p.id, p.name, p.price, p.stock, 
           c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
";
$res = $conn->query($sql);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
  

  /* 🌕 KSCandles - Candle Haven Unified Theme */
/* Elegant Warm Creamy Candlelight Design */

:root {
  --bg: linear-gradient(135deg, #F8F5F0, #F4EFEA);
  --accent: linear-gradient(135deg, #C4B5A0, #8B7355);
  --card-bg: #ffffffcc;
  --text-dark: #3b2f2f;
  --text-light: #6b5c4c;
  --border: #e6ddd1;
  --hover-bg: #faf7f3;
  --success: #d1fae5;
  --error: #fee2e2;
}

body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: var(--bg);
  color: var(--text-dark);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* 🔖 Header */
header {
  background: var(--accent);
  color: #fff;
  padding: 16px 28px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
  border-bottom: 1px solid rgba(255, 255, 255, 0.25);
}

header h1 {
  font-family: 'Playfair Display', serif;
  font-weight: 600;
  font-size: 1.6rem;
  margin: 0;
  letter-spacing: 0.5px;
}

header .btnn {
  background: transparent;
  color: #fff;
  text-decoration: none;
  padding: 8px 15px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
}
header .btnn:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
}

/* 🕯️ Buttons */
header .btn {
  background: #fff;
  color: #8B7355;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
header .btn:hover {
  background: #f0eae4;
  color: #3b2f2f;
  transform: translateY(-2px);
}

/* 📋 Main Container */
main {
  flex: 1;
  padding: 30px;
  max-width: 1100px;
  margin: 0 auto;
  width: 100%;
}

/* ✅ Alerts */
.alert {
  background: #f6ffed;
  color: #4b6043;
  padding: 14px 16px;
  border-radius: 10px;
  font-weight: 500;
  margin-bottom: 25px;
  border: 1px solid #d9f7be;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

/* 🧾 Table */
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 14px;
  overflow: hidden;
  background: var(--card-bg);
  backdrop-filter: blur(8px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

th, td {
  padding: 14px 18px;
  text-align: left;
  border-bottom: 1px solid var(--border);
  font-size: 15px;
}

th {
  background: var(--accent);
  color: #fff;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.6px;
  font-weight: 600;
}

td {
  color: var(--text-light);
  transition: background 0.2s ease;
}

tr:hover td {
  background: var(--hover-bg);
}

/* ✨ Actions */
.actions a {
  text-decoration: none;
  padding: 7px 11px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  transition: all 0.25s ease;
}

.actions a.edit {
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  color: #fff;
}
.actions a.edit:hover {
  background: linear-gradient(135deg, #8B7355, #C4B5A0);
  transform: translateY(-2px);
}

.actions a.delete {
  background: linear-gradient(135deg, #e57373, #c62828);
  color: #fff;
}
.actions a.delete:hover {
  background: linear-gradient(135deg, #c62828, #b71c1c);
  transform: translateY(-2px);
}

/* 📱 Responsive Design */
@media (max-width: 768px) {
  table, thead, tbody, th, td, tr { display: block; }
  thead { display: none; }
  tr {
    background: #fff;
    margin-bottom: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
  }
  td {
    display: flex;
    justify-content: space-between;
    padding: 12px 15px;
    border-bottom: 1px solid #f1ebe3;
  }
  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--text-dark);
  }
}

  </style>
</head>
<body>

<header>
  <a href="index.php" class="btnn"><h1>🛍️ Product Management</h1></a>
  
  <div>
    <a href="add_product.php" class="btn">➕ Add Product</a>
    <a href="index.php" class="btn">🏠 Dashboard</a>
  </div>
</header>

<main>
  <?php if(isset($_SESSION['msg'])): ?>
    <div class="alert"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Product</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($res && $res->num_rows > 0): ?>
        <?php while($p = $res->fetch_assoc()): ?>
          <tr>
            <td data-label="ID"><?= $p['id'] ?></td>
            <td data-label="Product"><?= htmlspecialchars($p['name']) ?></td>
            <td data-label="Category"><?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></td>
            <td data-label="Price">₹ <?= number_format($p['price'], 2) ?></td>
            <td data-label="Stock"><?= (int)$p['stock'] ?></td>
            <td data-label="Actions" class="actions">
              <a href="edit_product.php?id=<?= $p['id'] ?>" class="edit">Edit</a>
              <a href="delete_product.php?id=<?= $p['id'] ?>" class="delete" onclick="return confirmDelete(event)">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align:center;padding:20px;">No products found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>

<script>
function confirmDelete(e) {
  if (!confirm("Are you sure you want to delete this product?")) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>

</body>
</html>
