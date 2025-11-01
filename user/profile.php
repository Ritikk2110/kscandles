<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
if (!isset($_SESSION['user_id'])) header('Location: /kscandles/login.php');
$uid = $_SESSION['user_id'];
$user = $conn->query("SELECT id,name,email FROM users WHERE id = $uid")->fetch_assoc();

// change password
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_pass'])) {
    $old = $_POST['old_password']; 
    $new = $_POST['new_password'];
    $row = $conn->query("SELECT password FROM users WHERE id = $uid")->fetch_assoc();
    $stored = $row['password'];
    if (password_verify($old, $stored) || md5($old) === $stored) {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHash, $uid); 
        $stmt->execute();
        $success = "Password changed successfully.";
    } else $error = "Old password is incorrect.";
}

$orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$orders->bind_param("i", $uid); 
$orders->execute(); 
$ords = $orders->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Your Profile - <?= SITE_NAME ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  /* ===== Global Reset ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

/* ===== Body ===== */
body {
  background: linear-gradient(135deg, #f5eee6, #f8f4ef);
  min-height: 100vh;
  color: #2d2424;
}

/* ===== Headings ===== */
h1, h2 {
  text-align: center;
  background: linear-gradient(90deg, #978760ff, #c4b5a0);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 700;
}

h1 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

h2 {
  color: #8B7355;
  font-size: 1.4rem;
  margin-top: 2.5rem;
  margin-bottom: 1rem;
}

/* ===== Profile Container ===== */
.profile-container {
  max-width: 900px;
  margin: 40px auto;
  background: #fffdf9;
  padding: 35px 45px;
  border-radius: 18px;
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.profile-container:hover {
  transform: translateY(-5px);
}

/* ===== User Info ===== */
.user-info {
  text-align: center;
  margin-bottom: 20px;
}

.user-info p {
  font-size: 1.05rem;
  margin: 8px 0;
  color: #3d3227;
}

/* ===== Success & Error Messages ===== */
.success {
  color: #39e578ff;
  font-weight: 600;
  text-align: center;
  background: rgba(74, 222, 128, 0.1);
  padding: 10px;
  border-radius: 8px;
}

.error {
  color: #ef4444;
  font-weight: 600;
  text-align: center;
  background: rgba(239, 68, 68, 0.1);
  padding: 10px;
  border-radius: 8px;
}

/* ===== Form ===== */
form {
  text-align: center;
  margin: 25px 0 40px;
}

input[type="password"] {
  width: 65%;
  padding: 12px;
  margin: 8px;
  border-radius: 10px;
  border: 1px solid #c4b5a0;
  background: #fffaf5;
  font-size: 1rem;
  transition: all 0.3s ease;
}

input[type="password"]:focus {
  border-color: #8b7355;
  box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.25);
  outline: none;
}

/* ===== Button ===== */
.btn {
  
  background: linear-gradient(135deg, #8B7355, #837766ff);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px 25px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  text-align: center;
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(135deg, #c4b5a0, #8b7355);
  box-shadow: 0 6px 20px rgba(139, 115, 85, 0.4);
  transform: translateY(-2px);
}

/* ===== Table ===== */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
  background: #fffaf5;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

thead {
  background: linear-gradient(90deg, #8b7355, #c4b5a0);
  color: #fff;
}

th, td {
  padding: 14px 16px;
  text-align: center;
  border-bottom: 1px solid rgba(0,0,0,0.05);
  font-size: 0.95rem;
}

tr:hover {
  background: rgba(139, 115, 85, 0.05);
}

/* ===== Table Links ===== */
td a {
  color: #8b7355;
  text-decoration: none;
  font-weight: 500;
}

td a:hover {
  text-decoration: underline;
  color: #5c4a33;
}

/* ===== Responsive Design ===== */
@media (max-width: 768px) {
  input[type="password"] {
    width: 90%;
  }

  table, thead, tbody, th, td, tr {
    display: block;
  }

  thead {
    display: none;
  }

  tr {
    background: #fffdf9;
    margin-bottom: 15px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    padding: 12px;
  }

  td {
    display: flex;
    justify-content: space-between;
    padding: 10px 5px;
    border: none;
    font-size: 0.95rem;
  }

  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: #5c5045;
  }
}

  </style>
</head>
<body>

<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="profile-container">
  <h1>Your Profile</h1>

  <div class="user-info">
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
  </div>

  <h2>Change Password</h2>
  <?php if ($success) echo "<p class='success'>$success</p>"; ?>
  <?php if ($error) echo "<p class='error'>$error</p>"; ?>

  <form method="post">
    <input type="password" name="old_password" placeholder="Old Password" required>
    <input type="password" name="new_password" placeholder="New Password" required>
    <button name="change_pass" class="btn">Change Password</button>
  </form>

  <h2>Your Orders</h2>
  <?php if ($ords->num_rows > 0): ?>
  <table>
    <thead>
      <tr><th>ID</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php while($o = $ords->fetch_assoc()): ?>
      <tr>
        <td data-label="Order ID"><?= $o['id'] ?></td>
        <td data-label="Total">₹ <?= number_format($o['total'],2) ?></td>
        <td data-label="Status"><?= htmlspecialchars($o['status']) ?></td>
        <td data-label="Date"><?= htmlspecialchars($o['order_date']) ?></td>
        <td data-label="Action"><a href="/kscandles/user/order_details.php?id=<?= $o['id'] ?>">View</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p style="text-align:center; color:#666;">You haven’t placed any orders yet.</p>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
