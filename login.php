<?php
session_start();
require_once __DIR__ . '/includes/db.php';
function redirect($url) {
    header("Location: $url");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && (password_verify($password, $user['password']) || md5($password) === $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        redirect('/kscandles/index.php');
    } else {
        $error = "Invalid login credentials. Please try again.";
    }
}
?>



<style>



/* ===== Candle Haven Login Theme ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

/* ===== Page Background ===== */
body {
  background: linear-gradient(135deg, #FFF8F0, #F4E1C6);
  background-size: 200% 200%;
  animation: gradientShift 10s ease infinite;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  color: #4B3D2A;
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* ===== Container ===== */
.login-container {
  background: rgba(255, 255, 255, 0.25);
  border: 1px solid rgba(196, 181, 160, 0.4);
  border-radius: 18px;
  padding: 2.5rem 2rem;
  width: 100%;
  max-width: 420px;
  text-align: center;
  backdrop-filter: blur(12px);
  box-shadow: 0 8px 28px rgba(139, 115, 85, 0.25);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-container:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(139, 115, 85, 0.3);
}

/* ===== Heading ===== */
h1 {
  font-size: 2rem;
  margin-bottom: 1.5rem;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 700;
  letter-spacing: 1px;
}

/* ===== Inputs ===== */
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 1.2rem;
  border: none;
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.4);
  color: #5A4632;
  font-size: 1rem;
  outline: none;
  transition: all 0.3s ease;
}

input::placeholder {
  color: rgba(90, 70, 50, 0.6);
}

input:focus {
  background: rgba(255, 255, 255, 0.6);
  box-shadow: 0 0 10px rgba(196, 181, 160, 0.4);
}

/* ===== Button ===== */
.btn {
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(135deg, #BFA88B, #9A7C5A);
  box-shadow: 0 4px 18px rgba(139, 115, 85, 0.35);
  transform: translateY(-2px);
}

/* ===== Error Message ===== */
.error {
  color: #ff4d4d;
  background: rgba(255, 240, 240, 0.6);
  border: 1px solid rgba(255, 120, 120, 0.3);
  padding: 8px 12px;
  border-radius: 8px;
  margin-bottom: 1rem;
  display: inline-block;
  font-size: 0.9rem;
  font-weight: 500;
}

/* ===== Text Links ===== */
p {
  margin-top: 1rem;
  color: #5A4632;
  font-weight: 500;
}

a {
  color: #8B7355;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

a:hover {
  color: #9A7C5A;
}

/* ===== Back Button ===== */
.back-btn {
  display: inline-block;
  margin-top: 1.5rem;
  background: rgba(255, 255, 255, 0.35);
  color: #5A4632;
  font-size: 0.95rem;
  padding: 10px 16px;
  border-radius: 10px;
  transition: all 0.3s ease;
  font-weight: 500;
}

.back-btn:hover {
  background: rgba(255, 255, 255, 0.55);
  box-shadow: 0 3px 10px rgba(139, 115, 85, 0.15);
}

/* ===== Responsive ===== */
@media (max-width: 480px) {
  .login-container {
    padding: 1.8rem 1.2rem;
  }
  h1 {
    font-size: 1.7rem;
  }
}
</style>

<div class="login-page">
  <div class="login-container">
    <h1>Login</h1>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
      <input name="email" type="email" placeholder="Enter your email" required>
      <input name="password" type="password" placeholder="Enter your password" required>
      <button class="btn" type="submit">Login</button>
      <p>Don't have an account? <a href="/kscandles/register.php">Register</a></p>
    </form>

    <a href="/kscandles/index.php" class="back-btn">← Back to Home</a>
  </div>
</div>





