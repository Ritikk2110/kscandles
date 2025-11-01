<?php
// admin/users.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch users
$res = $conn->query("SELECT id, name, email, phone, created_at FROM users ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Users - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
 
  <style>
    /* small helper tweaks for users table */
    .users-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .users-header h2 { margin:0; color:#333; }
    .small-muted { color:#666; font-size:0.9rem; }
    .action-links a { margin-right:8px; color: #ff7b00; text-decoration:none; }
    .action-links a:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <style>


/* ================================================
   KSCandles — Unified Admin Users Page Styling
   ================================================ */

:root {
  --bg-gradient: linear-gradient(135deg, #f7f2eb, #fffaf3);
  --accent: #8b6f47;
  --accent-gradient: linear-gradient(135deg, #b59766, #d9c2a3);
  --accent-hover: linear-gradient(135deg, #c2a36d, #e4d2b3);
  --danger: linear-gradient(135deg, #b94c4c, #8b2b2b);
  --danger-hover: linear-gradient(135deg, #c45c5c, #9c3333);
  --info: linear-gradient(135deg, #b79d6b, #d1b889);
  --font-body: "Poppins", sans-serif;
  --font-heading: "Cormorant Garamond", serif;
  --panel-bg: rgba(255, 255, 255, 0.85);
  --shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
  --radius: 18px;
}

/* === Base === */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: var(--bg-gradient);
  font-family: var(--font-body);
  color: #3b2f2f;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* === Header Navigation === */
header {
  width: 100%;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(8px);
  border-bottom: 1px solid rgba(139, 111, 71, 0.2);
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 15px;
  padding: 14px 0;
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

header .btn {
  text-decoration: none;
  background: var(--accent-gradient);
  color: #fff;
  padding: 9px 20px;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
}

header .btn:hover,
header .btn.active {
  background: var(--accent-hover);
  box-shadow: 0 4px 15px rgba(181, 151, 102, 0.4);
  transform: translateY(-2px);
}

/* === Main Container === */
main {
  width: 95%;
  max-width: 1100px;
  background: var(--panel-bg);
  backdrop-filter: blur(18px);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  margin-top: 40px;
  padding: 35px 40px;
  animation: fadeIn 0.8s ease-in-out;
}

/* === Header Section === */
.users-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  flex-wrap: wrap;
}

.users-header h2 {
  font-family: var(--font-heading);
  font-size: 1.9rem;
  color: var(--accent);
  margin-bottom: 10px;
  position: relative;
}

.users-header h2::after {
  content: "";
  position: absolute;
  bottom: -6px;
  left: 0;
  width: 60px;
  height: 3px;
  background: var(--accent-gradient);
  border-radius: 2px;
}

.users-header .btn {
  background: var(--accent-gradient);
  padding: 8px 18px;
  border-radius: 20px;
  font-size: 0.9rem;
  color: #fff;
  text-decoration: none;
  transition: all 0.3s ease;
}

.users-header .btn:hover {
  background: var(--accent-hover);
}

/* === Alerts === */
.alert {
  padding: 12px 18px;
  border-radius: 12px;
  margin-bottom: 20px;
  font-weight: 500;
  color: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.alert.success {
  background: linear-gradient(135deg, #7fb77e, #a0d69b);
}

.alert.error {
  background: var(--danger);
}

/* === Table === */
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 15px;
  overflow: hidden;
  background: rgba(255, 255, 255, 0.6);
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
}

thead {
  background: var(--accent-gradient);
}

thead th {
  color: #fff;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  padding: 14px;
  text-align: left;
}

tbody tr {
  border-bottom: 1px solid rgba(0, 0, 0, 0.04);
  transition: all 0.3s ease;
}

tbody tr:hover {
  background: rgba(240, 235, 225, 0.7);
  transform: scale(1.01);
}

td {
  padding: 14px 16px;
  font-size: 0.95rem;
  color: #4b3a2b;
}

/* === Action Links === */
.action-links a {
  text-decoration: none;
  font-weight: 500;
  border-radius: 8px;
  padding: 6px 12px;
  margin-right: 8px;
  transition: 0.3s ease;
  display: inline-block;
  color: #fff;
}

.action-links a:nth-child(1) {
  background: var(--accent-gradient);
}
.action-links a:nth-child(1):hover {
  background: var(--accent-hover);
  box-shadow: 0 3px 8px rgba(139, 111, 71, 0.3);
}

.action-links a:nth-child(2) {
  background: var(--info);
}
.action-links a:nth-child(2):hover {
  background: var(--accent-hover);
  box-shadow: 0 3px 8px rgba(215, 183, 130, 0.3);
}

.action-links a:nth-child(3) {
  background: var(--danger);
}
.action-links a:nth-child(3):hover {
  background: var(--danger-hover);
  box-shadow: 0 3px 8px rgba(185, 76, 76, 0.3);
}

/* === Empty Data Message === */
.no-data {
  text-align: center;
  padding: 30px 0;
  font-size: 1.1rem;
  color: #6b5b4b;
}

/* === Animation === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

/* === Responsive === */
@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }
  thead { display: none; }
  tbody tr {
    margin-bottom: 15px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    padding: 15px;
  }
  td {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 0.9rem;
  }
  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--accent);
  }
}


 </style>
<header>
  <a href="index.php" class="btn">Dashboard</a>
  <a href="products.php" class="btn">Products</a>
  <a href="users.php" class="btn active">Users</a>
  <a href="orders.php" class="btn">Orders</a>
  <a href="logout.php" class="btn">Logout</a>
</header>

<main class="orders-container">
  <div class="users-header">
    <h2>Registered Users</h2>
    <div>
      <!-- Optionally: export CSV, add user etc. -->
      <a href="export_users.php" class="btn">Export CSV</a>
    </div>
  </div>

  <?php if(isset($_SESSION['msg'])): ?>
    <div class="alert success"><?= htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
  <?php endif; ?>

  <table class="orders-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($u = $res->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['phone'] ?? '') ?></td>
          <td><?= htmlspecialchars($u['created_at'] ?? '-') ?></td>
          <td class="action-links">
            <a href="view_user.php?id=<?= $u['id'] ?>">View</a>
            <a href="edit_user.php?id=<?= $u['id'] ?>">Edit</a>
            <a href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirmDelete(event, 'user')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

<script>
function confirmDelete(e, itemName = 'item') {
  if (!confirm("Are you sure you want to delete this " + itemName + "? This action cannot be undone.")) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>
</body>
</html>
