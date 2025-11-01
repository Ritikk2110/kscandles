<?php
// admin/login.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';

// If already logged in, send to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, email, password FROM admin WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $admin = $res->fetch_assoc();
        $stmt->close();

        if ($admin) {
            $stored = $admin['password'];
            $valid = false;

            // Prefer password_hash / password_verify
            if (password_verify($password, $stored)) {
                $valid = true;
            } elseif (md5($password) === $stored) { // legacy MD5 fallback
                $valid = true;
            }

            if ($valid) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'Invalid credentials.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login - <?= htmlspecialchars('KSCandles') ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
 
  <style>
/* =============== ADMIN LOGIN PAGE STYLING =============== */


/* ================================
   KSCANDLES ADMIN LOGIN THEME
   Creamy White & Warm Candlelight
   Gradient: linear-gradient(135deg, #C4B5A0, #8B7355)
   Font: 'Poppins', sans-serif
================================= */

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
  color: #3a2f25;
  overflow: hidden;
}

/* Login Card */
.login-wrap {
  background: #fffaf4;
  padding: 45px 38px;
  border-radius: 18px;
  box-shadow: 0 12px 38px rgba(0, 0, 0, 0.15);
  width: 420px;
  max-width: 90%;
  animation: fadeIn 0.8s ease-in-out;
  border: 1px solid rgba(196, 181, 160, 0.3);
}

/* Fade-in animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Title */
.login-wrap h2 {
  text-align: center;
  color: #4a3b2e;
  margin-bottom: 10px;
  font-size: 26px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.login-foot h4 {
  text-align: center;
  color: #8b7355;
  font-weight: 500;
  font-size: 15px;
  margin-bottom: 25px;
}

/* Error Message */
.error {
  background: #fff1f1;
  border: 1px solid #ffbdbd;
  color: #a12727;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  text-align: center;
  font-size: 15px;
}

/* Labels */
.login-wrap label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: #5a4635;
  font-size: 15px;
}

/* Inputs */
.login-wrap input[type="text"],
.login-wrap input[type="password"] {
  width: 100%;
  padding: 12px 14px;
  margin-bottom: 20px;
  border: 1px solid #ddd2c0;
  border-radius: 10px;
  font-size: 15px;
  background: #fffefb;
  transition: all 0.3s ease;
}

.login-wrap input:focus {
  border-color: #8b7355;
  outline: none;
  box-shadow: 0 0 8px rgba(139, 115, 85, 0.35);
}

/* Buttons */
.login-wrap button,
.back-home {
  width: 100%;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  border: none;
  border-radius: 10px;
  color: #fffaf4;
  padding: 13px 0;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
  text-align: center;
  letter-spacing: 0.3px;
  box-shadow: 0 6px 20px rgba(139, 115, 85, 0.25);
}

.login-wrap button:hover,
.back-home:hover {
  background: linear-gradient(135deg, #b7a17e, #7a654a);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(139, 115, 85, 0.3);
}

/* Footer text */
.login-foot {
  text-align: center;
  margin-top: 18px;
  color: #7a6a55;
  font-size: 14px;
}

/* Mobile Responsive */
@media (max-width: 480px) {
  .login-wrap {
    padding: 35px 25px;
    width: 92%;
  }
  .login-wrap h2 { font-size: 22px; }
  .login-wrap input { font-size: 14px; }
  .login-wrap button { font-size: 15px; }
}

  </style>
</head>
<body>
  <div class="login-wrap">
    <h2>Admin Login</h2>
    <div class="login-foot">
     <h4> Use your admin credentials to sign in.</h4>
    </div>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off" novalidate>
      <label for="email">Email</label>
      <input id="email" type="text" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>

      <label for="password">Password</label>
      <input id="password" type="password" name="password" required>

      <button type="submit">Sign In</button>
    </form>

    

    <a href="../index.php" class="back-home" style="margin-top:15px;display:block;">← Back to Home</a>
  </div>
</body>
</html>
