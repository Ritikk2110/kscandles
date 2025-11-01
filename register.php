<?php
require_once __DIR__ . '/includes/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); 
    $email = trim($_POST['email']); 
    $password = $_POST['password'];

    // ✅ Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "An account with this email already exists. Please use a different email or login.";
    } else {
        // ✅ Hash password and insert new user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name,email,password,created_at) VALUES (?,?,?,NOW())");
        $stmt->bind_param("sss", $name, $email, $hash);
        if ($stmt->execute()) {
            $success = "Account created successfully! Redirecting to login...";
            echo "<script>
                    setTimeout(function(){
                        window.location.href = '/kscandles/login.php';
                    }, 1800);
                  </script>";
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    }
}
?>
<style>
/* ======================================================
   KSCANDLES - AUTH PAGE THEME (Login + Register Unified)
   Palette: Creamy White | Candlelight Gradient
   ====================================================== */

/* ===== Base Layout ===== */
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg, #fffaf3, #f7f3eb);
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
  color: #2c2c2c;
}

/* ===== Container ===== */
.auth-container {
  width: 100%;
  max-width: 420px;
  background: #ffffff;
  border-radius: 20px;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  border: 1px solid rgba(0,0,0,0.05);
  text-align: center;
  animation: fadeInUp 0.7s ease;
  transition: all 0.3s ease;
}

.auth-container:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(0,0,0,0.12);
}

/* ===== Heading ===== */
.auth-container h1 {
  margin-bottom: 1.5rem;
  font-size: 2rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ===== Form Inputs ===== */
input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px 14px;
  margin-bottom: 1rem;
  border: 1px solid #d8d2c4;
  border-radius: 12px;
  outline: none;
  background: #faf9f7;
  font-size: 1rem;
  color: #333;
  transition: all 0.25s ease;
}

input::placeholder {
  color: #9b9488;
}

input:focus {
  border-color: #C4B5A0;
  background: #fff;
  box-shadow: 0 0 6px rgba(196,181,160,0.3);
}

/* ===== Buttons ===== */
.btn {
  width: 100%;
  padding: 12px 20px;
  font-size: 1rem;
  font-weight: 600;
  text-transform: uppercase;
  color: #fff;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  box-shadow: 0 4px 10px rgba(139,115,85,0.25);
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(135deg, #8B7355, #C4B5A0);
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(139,115,85,0.35);
}

/* ===== Messages ===== */
.error, .success {
  padding: 10px 12px;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-weight: 500;
}

.error {
  color: #a52a2a;
  background: #fff2f2;
  border: 1px solid #f1d0d0;
}

.success {
  color: #2e7d32;
  background: #e9f7ef;
  border: 1px solid #c8e6c9;
}

/* ===== Links ===== */
p {
  margin-top: 1rem;
  color: #5a534a;
  font-size: 0.95rem;
}

a {
  color: #8B7355;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.3s ease;
}

a:hover {
  color: #C4B5A0;
}

/* ===== Back Home ===== */
.back-home {
  display: inline-block;
  margin-top: 1rem;
  color: #555;
  opacity: 0.8;
  transition: opacity 0.3s ease, color 0.3s ease;
}

.back-home:hover {
  opacity: 1;
  color: #8B7355;
}

/* ===== Animation ===== */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(25px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ===== Responsive ===== */
@media (max-width: 480px) {
  .auth-container {
    padding: 2rem 1.5rem;
  }
  .auth-container h1 {
    font-size: 1.7rem;
  }
}
</style>

<div class="auth-container">
  <h1>Create Account</h1>

  <?php 
    if (!empty($error)) echo "<p class='error'>$error</p>"; 
    if (!empty($success)) echo "<p class='success'>$success</p>"; 
  ?>

  <form method="post">
    <input name="name" type="text" placeholder="Full Name" required>
    <input name="email" type="email" placeholder="Email Address" required>
    <input name="password" type="password" placeholder="Password" required>
    <button class="btn" type="submit">Register</button>

    <p>Already have an account? <a href="/kscandles/login.php">Login here</a></p>
    <a href="/kscandles/index.php" class="back-home">← Back to Home</a>
  </form>
</div>
