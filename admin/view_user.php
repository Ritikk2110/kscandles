<?php
// admin/view_user.php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get user ID safely
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['msg'] = "Invalid user id.";
    header('Location: users.php');
    exit;
}

// Fetch user details
$stmt = $conn->prepare("SELECT id, name, email, phone, address, created_at FROM users WHERE id = ? LIMIT 1");
if (!$stmt) {
    die("DB error: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

if (!$user) {
    $_SESSION['msg'] = "User not found.";
    header('Location: users.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>View User #<?= htmlspecialchars($user['id']) ?> - Admin Panel | KSCandles</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    


    /* === Candle Haven Admin View User Page === */
:root {
  --bg-gradient: linear-gradient(135deg, #C4B5A0, #8B7355);
  --card-bg: rgba(255, 255, 255, 0.95);
  --accent-gradient: linear-gradient(135deg, #D7C2A8, #A68B6C);
  --danger-gradient: linear-gradient(135deg, #E57373, #C62828);
  --text-dark: #3B2F2F;
  --text-muted: #6B5B4B;
  --white: #fff;
  --shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--bg-gradient);
  margin: 0;
  padding: 0;
  color: var(--text-dark);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* === Header === */
header {
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  padding: 14px 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  position: sticky;
  top: 0;
  z-index: 10;
}

header .btn,
header .btn-secondary {
  display: inline-block;
  text-decoration: none;
  padding: 9px 18px;
  font-weight: 600;
  border-radius: 10px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.btn {
  background: var(--accent-gradient);
  color: var(--white);
  border: none;
}

.btn:hover {
  background: linear-gradient(135deg, #B99C7B, #8B7355);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.8);
  color: var(--text-dark);
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.95);
}

/* === Main Container === */
main {
  padding: 50px 20px;
  display: flex;
  justify-content: center;
}

.detail {
  width: 100%;
  max-width: 850px;
  background: var(--card-bg);
  padding: 40px;
  border-radius: 20px;
  box-shadow: var(--shadow);
  animation: fadeIn 0.8s ease-in-out;
}

.detail h2 {
  text-align: center;
  color: var(--text-dark);
  font-size: 1.8rem;
  margin-bottom: 30px;
  background: var(--accent-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* === User Details === */
.row {
  display: flex;
  flex-wrap: wrap;
  margin: 12px 0;
  border-bottom: 1px solid #eee;
  padding-bottom: 8px;
}

.label {
  flex: 0 0 180px;
  color: var(--text-muted);
  font-weight: 600;
  font-size: 0.95rem;
}

.value {
  flex: 1;
  color: var(--text-dark);
  font-size: 0.95rem;
}

/* === Buttons === */
.actions {
  display: flex;
  justify-content: center;
  gap: 18px;
  margin-top: 30px;
}

.actions .btn {
  min-width: 130px;
  text-align: center;
}

.btn-delete {
  background: var(--danger-gradient);
}

.btn-delete:hover {
  background: linear-gradient(135deg, #D32F2F, #B71C1C);
  box-shadow: 0 5px 15px rgba(183, 28, 28, 0.4);
}

/* === Animations === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* === Responsive Design === */
@media (max-width: 768px) {
  .detail {
    padding: 25px 20px;
    margin: 20px;
  }
  .label {
    flex: 1 0 100%;
    margin-bottom: 4px;
  }
  .value {
    flex: 1 0 100%;
    margin-bottom: 10px;
  }
  .actions {
    flex-direction: column;
    gap: 10px;
  }
}

  </style>
</head>
<body>

<header>
  <a href="users.php" class="btn-secondary">← Back to Users</a>
  <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn" style="margin-left:12px;">Edit</a>
  <a href="delete_user.php?id=<?= $user['id'] ?>" 
     class="btn" 
     style="background: linear-gradient(135deg, #ff416c, #ff4b2b); margin-left:12px;"
     onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
     Delete
  </a>
</header>

<main>
  <div class="detail">
    <h2>User #<?= htmlspecialchars($user['id']) ?> — <?= htmlspecialchars($user['name']) ?></h2>

    <div class="row">
      <div class="label">Email:</div>
      <div class="value"><?= htmlspecialchars($user['email']) ?></div>
    </div>

    <div class="row">
      <div class="label">Phone:</div>
      <div class="value"><?= htmlspecialchars($user['phone'] ?: '-') ?></div>
    </div>

    <div class="row">
      <div class="label">Address:</div>
      <div class="value"><?= nl2br(htmlspecialchars($user['address'] ?: '-')) ?></div>
    </div>

    <div class="row">
      <div class="label">Joined At:</div>
      <div class="value"><?= htmlspecialchars($user['created_at'] ?? '-') ?></div>
    </div>

    <div class="actions">
      <a href="users.php" class="btn-secondary">Close</a>
      <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn">Edit User</a>
    </div>
  </div>
</main>

</body>
</html>
