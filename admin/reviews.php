<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM reviews WHERE id = $id");
  header('Location: reviews.php');
  exit;
}
$res = $conn->query("SELECT r.*, p.name AS product_name FROM reviews r LEFT JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC");
?>
<!doctype html><html><head><title>Reviews</title></head><body>

<header>
  <a href="index.php" class="btn">Dashboard</a>
  <a href="products.php" class="btn">Products</a>
  <a href="users.php" class="btn">Users</a>
  <a href="orders.php" class="btn">Orders</a>
  <a href="reviews.php" class="btn active">Reviews</a>
  <a href="logout.php" class="btn">Logout</a>
</header>

<h1>Reviews</h1>
<table border="1"><thead><tr><th>#</th><th>Product</th><th>Name</th><th>Rating</th><th>Comment</th><th>Date</th><th>Action</th></tr></thead><tbody>
<?php while($row=$res->fetch_assoc()): ?>
<tr>
<td><?=$row['id']?></td>
<td><?=htmlspecialchars($row['product_name'])?></td>
<td><?=htmlspecialchars($row['name'])?></td>
<td><?= (int)$row['rating'] ?></td>
<td><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
<td><?= $row['created_at'] ?></td>
<td><a href="?delete=<?=$row['id']?>" onclick="return confirm('Delete?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody></table>
<style>


/* ==========================================
   KSCandles Admin Panel — Reviews Page
   Elegant Handcrafted Brown-Gold Theme
   ========================================== */

/* ---- Font Imports ---- */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Poppins:wght@400;500;600&display=swap');

/* ---- Global ---- */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  background: linear-gradient(135deg, #f6f1eb, #efe3d8);
  color: #3b2f2f;
  padding: 30px;
  min-height: 100vh;
}

/* ---- Header ---- */
header {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 10px;
  padding: 14px 0;
  background: rgba(58, 45, 34, 0.95);
  border-radius: 14px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  backdrop-filter: blur(8px);
  margin-bottom: 35px;
}

header .btn {
  text-decoration: none;
  color: #fffdf6;
  padding: 10px 22px;
  margin: 0 6px;
  border-radius: 25px;
  background: linear-gradient(135deg, #a57c50, #7c5a35);
  font-weight: 500;
  letter-spacing: 0.3px;
  transition: all 0.3s ease;
  box-shadow: 0 3px 6px rgba(165, 124, 80, 0.3);
}

header .btn:hover,
header .btn.active {
  background: linear-gradient(135deg, #c4a676, #9a7444);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(165, 124, 80, 0.5);
}

/* ---- Heading ---- */
h1 {
  font-family: "Cormorant Garamond", serif;
  text-align: center;
  font-size: 2rem;
  color: #000000ff;
  letter-spacing: 0.5px;
  margin-bottom: 28px;
  text-shadow: 0 2px 6px rgba(80, 60, 40, 0.15);
}

/* ---- Table ---- */
table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
  animation: fadeIn 0.6s ease-in-out;
}

thead {
  background: linear-gradient(135deg, #a57c50, #8d6b40);
}

thead th {
  color: #fffdf6;
  font-family: "Cormorant Garamond", serif;
  font-weight: 600;
  padding: 14px 12px;
  letter-spacing: 0.6px;
  text-align: center;
  font-size: 1rem;
}

tbody tr {
  border-bottom: 1px solid rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

tbody tr:nth-child(even) {
  background-color: rgba(250, 244, 238, 0.8);
}

tbody tr:hover {
  background: rgba(232, 221, 208, 0.7);
  transform: scale(1.01);
}

td {
  padding: 13px 15px;
  text-align: center;
  font-size: 0.95rem;
  color: #3e2e1f;
}

/* ---- Action Buttons ---- */
td a {
  text-decoration: none;
  color: #fffefc;
  background: linear-gradient(135deg, #8b5e34, #b17a4a);
  padding: 8px 15px;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
  box-shadow: 0 3px 8px rgba(139, 94, 52, 0.3);
}

td a:hover {
  background: linear-gradient(135deg, #b68b57, #d3a86c);
  box-shadow: 0 5px 12px rgba(139, 94, 52, 0.4);
  transform: translateY(-2px);
}

/* ---- Responsive Table ---- */
@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }
  thead { display: none; }

  tbody tr {
    margin-bottom: 15px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.75);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 12px;
  }

  td {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    font-size: 0.9rem;
  }

  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: #8b5e34;
  }
}

/* ---- Animation ---- */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}

</style>




</body></html>
