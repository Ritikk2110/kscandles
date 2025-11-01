<?php
// profile.php - User profile / update page
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/db.php';

// ensure user logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /kscandles/login.php');
    exit;
}

$uid = (int)$_SESSION['user_id'];
$msg = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_profile') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $email === '') {
        $error = 'Name and email are required.';
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $uid);
        if ($stmt->execute()) {
            $msg = 'Profile updated successfully.';
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        } else {
            $error = 'Failed to update profile.';
        }
        $stmt->close();
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'change_password') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new === '' || $confirm === '' || $current === '') {
        $error = 'All password fields are required.';
    } elseif ($new !== $confirm) {
        $error = 'New password and confirm password do not match.';
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stored = $stmt->get_result()->fetch_assoc()['password'] ?? '';
        $stmt->close();

        if (!password_verify($current, $stored) && md5($current) !== $stored) {
            $error = 'Current password is incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $uid);
            if ($stmt->execute()) {
                $msg = 'Password changed successfully.';
            } else {
                $error = 'Failed to change password.';
            }
            $stmt->close();
        }
    }
}

// Fetch latest user data
$stmt = $conn->prepare("SELECT id,name,email,phone,address,created_at FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<h1>My Profile</h1>

<?php if ($msg): ?>
  <div class="alert success"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<style>
/* =====================================
   KSCANDLES — PROFILE PAGE DESIGN THEME
   Inspired by your example files (merged aesthetic)
   ===================================== */

body {
  /*
  font-family: "Poppins", sans-serif;*/
  background: linear-gradient(120deg, #fbf4ecff, #e8e0d4);
  color: #2b2b2b;
  margin: 0;
  padding: 0;
  line-height: 1.4;
  overflow-x: hidden;
}

/* ------------------------------
   PAGE TITLE
--------------------------------*/
h1 {
  text-align: center;
  margin: 60px 0 30px;
  font-size: 2.6rem;
  font-weight: 600;
  color: #8B7355;
 /* color: #2d2d2d;*/
  letter-spacing: 0.6px;
  text-transform: uppercase;
  background: linear-gradient(90deg, #8B7355, #8B7355);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ------------------------------
   ALERT MESSAGES
--------------------------------*/
.alert {
  max-width: 900px;
  margin: 10px auto;
  padding: 14px 20px;
  border-radius: 10px;
  font-weight: 500;
  text-align: center;
  font-size: 1rem;
  animation: fadeIn 0.5s ease;
}
.alert.success {
  background: #eafaf0;
  color: #2a7b3f;
  border: 1px solid #a8e2b4;
}
.alert.error {
  background: #fdeaea;
  color: #b23030;
  border: 1px solid #e09a9a;
}

/* ------------------------------
   PROFILE GRID
--------------------------------*/
.profile-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(370px, 1fr));
  gap: 40px;
  max-width: 1100px;
  margin: 40px auto 80px;
  padding: 0 20px;
  animation: slideUp 0.6s ease;
}

/* ------------------------------
   PROFILE CARD STYLING
--------------------------------*/
.profile-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  padding: 35px 28px;
  transition: all 0.4s ease;
  border: 1px solid rgba(0, 0, 0, 0.05);
}
.profile-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
}

/* ------------------------------
   CARD HEADINGS
--------------------------------*/
.profile-card h2 {
  margin-bottom: 25px;
  color: #444;
  font-size: 1.5rem;
  border-left: 5px solid #c58940;
  padding-left: 12px;
  font-weight: 600;
  letter-spacing: 0.3px;
}

/* ------------------------------
   FORM DESIGN
--------------------------------*/
.profile-card form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
.profile-card label {
  font-weight: 600;
  color: #555;
  margin-bottom: 4px;
}
.profile-card input,
.profile-card textarea {
  border: 1px solid #ccc;
  border-radius: 10px;
  padding: 12px 14px;
  font-size: 15px;
  transition: all 0.25s ease;
  background: #fafafa;
}
.profile-card input:focus,
.profile-card textarea:focus {
  border-color: #c58940;
  background: #fff;
  box-shadow: 0 0 6px rgba(197, 137, 64, 0.25);
}

/* ------------------------------
   BUTTONS
--------------------------------*/
.btn {
  display: inline-block;
      background: linear-gradient(135deg, #8B7355, #cbaa81);
 /* background: linear-gradient(135deg, #c58940, #a67435);*/
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px 20px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  text-transform: uppercase;
  text-align: center;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 10px rgba(197, 137, 64, 0.3);
}
.btn:hover {
  background: linear-gradient(135deg, #a67435, #c58940);
  transform: translateY(-3px);
  box-shadow: 0 6px 14px rgba(197, 137, 64, 0.4);
}

/* ------------------------------
   ORDERS BUTTON SECTION
--------------------------------*/
.orders-link {
  text-align: center;
  margin: 30px 0 70px;
}
.orders-link .btn {
  padding: 12px 28px;
  font-size: 16px;
}

/* ------------------------------
   ANIMATIONS
--------------------------------*/
@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* ------------------------------
   RESPONSIVE DESIGN
--------------------------------*/
@media (max-width: 768px) {
  h1 {
    font-size: 1.9rem;
  }
  .profile-card {
    padding: 25px 20px;
  }
  .btn {
    width: 100%;
  }
}

</style>



<section class="profile-grid">
  <div class="profile-card">
    <h2>Account Details</h2>
    <form method="post">
      <input type="hidden" name="action" value="update_profile">
      <label>Name</label>
      <input name="name" type="text" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
      <label>Email</label>
      <input name="email" type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
      <label>Phone</label>
      <input name="phone" type="text" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
      <label>Address</label>
      <textarea name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
      <button class="btn" type="submit">Save Profile</button>
    </form>
  </div>

  <div class="profile-card">
    <h2>Change Password</h2>
    <form method="post">
      <input type="hidden" name="action" value="change_password">
      <label>Current Password</label>
      <input name="current_password" type="password" placeholder="Enter current password" required>
      <label>New Password</label>
      <input name="new_password" type="password" placeholder="Enter new password" required>
      <label>Confirm New Password</label>
      <input name="confirm_password" type="password" placeholder="Confirm new password" required>
      <button class="btn" type="submit">Change Password</button>
    </form>
  </div>
</section>

<section class="orders-link">
  <a href="/kscandles/orders.php" class="btn">View My Orders</a>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

