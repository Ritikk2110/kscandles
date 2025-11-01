<?php
// success.php — shown after successful registration
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Successful</title>
<style>
/* ===== Global Reset ===== */
/* ================================
   KS CANDLES — SUCCESS PAGE THEME
   ================================ */

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

/* ===== Body Background ===== */
body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5ebe0, #f9f6f1, #fffaf5);
  background-size: 200% 200%;
  animation: softGlow 10s ease-in-out infinite;
  color: #3a2e2e;
  text-align: center;
}

/* ===== Subtle background motion ===== */
@keyframes softGlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* ===== Success Container ===== */
.success-box {
  background: #fffaf5;
  border: 1px solid rgba(196, 164, 132, 0.3);
  border-radius: 20px;
  padding: 3rem 2rem;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  animation: fadeInUp 1s ease;
}

/* ===== Fade Animation ===== */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ===== Success Icon ===== */
.success-icon {
  font-size: 4rem;
  color: #6bbf59;
  text-shadow: 0 0 10px rgba(107, 191, 89, 0.4);
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

/* ===== Title & Message ===== */
h1 {
  font-size: 2rem;
  margin-top: 1rem;
  color: #2d2424;
  font-family: "Cormorant Garamond", serif;
  letter-spacing: 1px;
}

p {
  margin-top: 1rem;
  color: #4a3d3d;
  font-size: 1rem;
}

/* ===== Primary Button ===== */
.btn {
  margin-top: 2rem;
  background: linear-gradient(90deg, #d8b384, #c4a484);
  color: #fff;
  border: none;
  padding: 12px 25px;
  border-radius: 10px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
  font-weight: 600;
}

.btn:hover {
  background: linear-gradient(90deg, #c4a484, #b38b68);
  box-shadow: 0 4px 20px rgba(180, 140, 90, 0.4);
  transform: translateY(-2px);
}

/* ===== Back Home Link ===== */
.back-home {
  display: inline-block;
  margin-top: 1rem;
  color: #8b6f47;
  opacity: 0.9;
  text-decoration: none;
  transition: color 0.3s ease, opacity 0.3s ease;
}

.back-home:hover {
  opacity: 1;
  color: #b38b68;
}

</style>
</head>
<body>

<div class="success-box">
  <div class="success-icon">✔️</div>
  <h1>Registration Successful!</h1>
  <p>You’ll be redirected to the login page in <span id="countdown">3</span> seconds...</p>
  <a href="/kscandles/login.php" class="btn">Go to Login</a>
  <br>
  <a href="/kscandles/index.php" class="back-home">← Back to Home</a>
</div>

<script>
  // Countdown and redirect
  let timeLeft = 3;
  const countdown = document.getElementById('countdown');
  const timer = setInterval(() => {
    timeLeft--;
    countdown.textContent = timeLeft;
    if (timeLeft <= 0) {
      clearInterval(timer);
      window.location.href = '/kscandles/login.php';
    }
  }, 1000);
</script>

</body>
</html>
