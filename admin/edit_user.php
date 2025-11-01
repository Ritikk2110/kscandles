<?php
// admin/edit_user.php
session_start();
require_once __DIR__ . '/../includes/db.php';

// protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// get and validate id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['msg'] = "Invalid user id.";
    header('Location: users.php');
    exit;
}

// fetch existing user
$stmt = $conn->prepare("SELECT id, name, email, phone, address FROM users WHERE id = ? LIMIT 1");
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

$errors = [];
// handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // validation
    if ($name === '') $errors[] = "Name is required.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";

    // check email uniqueness (exclude current user)
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($exists) $errors[] = "Email is already used by another account.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        if (!$stmt) {
            $errors[] = "DB error: " . $conn->error;
        } else {
            $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
            if ($stmt->execute()) {
                $_SESSION['msg'] = "User updated successfully.";
                $stmt->close();
                header('Location: users.php');
                exit;
            } else {
                $errors[] = "Failed to update user: " . $stmt->error;
                $stmt->close();
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit User #<?= htmlspecialchars($user['id']) ?> - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  
  <style>
    .form-card { max-width:800px; margin:30px auto; background:#fff; padding:22px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.08); }
    label { display:block; margin:8px 0 6px; color:#444; font-weight:600; }
    input, textarea { width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; }
    .actions { margin-top:14px; display:flex; gap:10px; }
    .error { background:#ffe6e6; color:#a33; padding:10px; border-radius:6px; margin-bottom:12px; }
  </style>
</head>
<body>

<header>
  <a href="users.php" class="btn">Back</a>
  <a href="view_user.php?id=<?= $user['id'] ?>" class="btn">View</a>
</header>

<main class="orders-container">
  <div class="form-card">
    <h2>Edit User <?= htmlspecialchars($user['id']) ?></h2>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach ($errors as $e): ?>
          <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <label for="name">Name</label>
      <input id="name" type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>" required>

      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>

      <label for="phone">Phone</label>
      <input id="phone" type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone']) ?>">

      <label for="address">Address</label>
      <textarea id="address" name="address" rows="4"><?= htmlspecialchars($_POST['address'] ?? $user['address']) ?></textarea>

      <div class="actions">
        <button type="submit" class="btn">Save Changes</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</main>


<style>    /* === General Reset & Base === */


/* ==============================================
   KSCANDLES ADMIN PANEL — EDIT USER PAGE
   Theme: Creamy White (Warm Candlelight)
   Gradient: linear-gradient(135deg, #C4B5A0, #8B7355)
   Font: 'Poppins', sans-serif
============================================== */

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

/* === RESET & BASE === */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  min-height: 100vh;
  color: #3c2e24;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* === HEADER NAVIGATION === */
header {
  width: 100%;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  display: flex;
  justify-content: center;
  gap: 18px;
  padding: 15px 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

header .btn {
  text-decoration: none;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  color: #fffaf4;
  padding: 10px 20px;
  border-radius: 30px;
  transition: all 0.3s ease;
  font-weight: 500;
  box-shadow: 0 4px 14px rgba(139, 115, 85, 0.35);
}

header .btn:hover {
  background: linear-gradient(135deg, #b8a58e, #7b6549);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(139, 115, 85, 0.45);
}

/* === MAIN SECTION === */
main {
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 50px 20px;
}

.form-card {
  width: 100%;
  max-width: 700px;
  background: #fffaf4;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  padding: 35px 40px;
  color: #3e3024;
  animation: fadeIn 0.8s ease-in-out;
  border: 1px solid rgba(196, 181, 160, 0.3);
}

/* === TITLE === */
.form-card h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #4d3a2a;
  font-size: 1.9rem;
  font-weight: 600;
  letter-spacing: 0.3px;
}

/* === LABELS & INPUTS === */
label {
  font-weight: 600;
  color: #6a5743;
  margin-top: 15px;
  display: block;
  margin-bottom: 6px;
}

input,
textarea {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #d9cbb3;
  border-radius: 10px;
  font-size: 15px;
  background: #fffefb;
  color: #3c2f24;
  outline: none;
  transition: all 0.3s ease;
}

input:focus,
textarea:focus {
  border-color: #8B7355;
  box-shadow: 0 0 8px rgba(139, 115, 85, 0.3);
}

/* === ERROR MESSAGE === */
.error {
  background: #fff1f1;
  border-left: 4px solid #e56b6b;
  padding: 12px 15px;
  border-radius: 8px;
  margin-bottom: 15px;
  color: #a33;
  font-size: 15px;
}

/* === BUTTONS === */
.actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 25px;
}

button.btn,
a.btn {
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  border: none;
  padding: 12px 25px;
  border-radius: 30px;
  color: #fffaf4;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(139, 115, 85, 0.25);
}

button.btn:hover,
a.btn:hover {
  background: linear-gradient(135deg, #bfa78a, #7b6549);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(139, 115, 85, 0.35);
}

a.btn.btn-secondary {
  background: linear-gradient(135deg, #d3c9ba, #9c8c7a);
  color: #fff;
}

a.btn.btn-secondary:hover {
  background: linear-gradient(135deg, #b8a68c, #7d6b54);
}

/* === ANIMATION === */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* === RESPONSIVE DESIGN === */
@media (max-width: 600px) {
  header {
    flex-wrap: wrap;
    gap: 10px;
  }

  .form-card {
    padding: 25px;
  }

  input,
  textarea {
    font-size: 14px;
  }
}

</style>

</body>
</html>
