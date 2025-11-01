<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>
<style>
/* ======== KSCANDLES THEME NAVBAR ======== */
.navbar {
  width: 100%;
  position: sticky;
  top: 0;
  z-index: 1000;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border-bottom: 1px solid rgba(200, 180, 150, 0.3);
}

/* Navbar container */
.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 12px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Logo Container */
.logo-container {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}

/* Logo Image in Circle */
.logo-img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #8B7355;
  background-color: #fff;
}

/* Logo Text */
.logo-text {
  /*
  font-family: "Cormorant Garamond", serif;*/
  font-size: 1.2rem;
  font-weight: 900;
  color: #907555ff;
  text-decoration: none;
  letter-spacing: 1px;
  transition: all 0.3s ease;
}

.logo-text:hover {
  color: #C4B5A0;
  transform: scale(1.05);
}

/* Navigation Links */
.nav-links {
  display: flex;
  align-items: center;
  gap: 18px;
}

.nav-links a {
  color: #2D2424;
  text-decoration: none;
  font-family: 'Poppins', sans-serif;
 
  font-weight: 500;
  font-size: 0.97rem;
  padding: 8px 14px;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.nav-links a:hover {
  background: rgba(200, 180, 150, 0.15);
  color: #8B7355;
}

/* Active Page Highlight */
.nav-links a.active {
  background: rgba(200, 180, 150, 0.25);
  color: #8B7355;
  font-weight: 600;
}

/* Buttons */
.btn {
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 1px solid transparent;
  font-family: "Inter", sans-serif;
}

/* Login / Register / Logout buttons */
.login-btn {
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  color: #fff;
  border: none;
}

.login-btn:hover {
  background: linear-gradient(135deg, #8B7355, #C4B5A0);
}

.register-btn {
  background: linear-gradient(135deg, #E8DCC2, #BFA47B);
  color: #2D2424;
}

.register-btn:hover {
  background: linear-gradient(135deg, #DCC6A0, #8B7355);
  color: #fff;
}

.logout-btn {
  background: linear-gradient(135deg, #8B7355, #C4B5A0);
  color: white;
}

.logout-btn:hover {
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
}

/* Cart */
.nav-links a[href*="cart.php"] {
  font-weight: 600;
  background: rgba(200, 180, 150, 0.15);
  border-radius: 8px;
  padding: 8px 16px;
}

.nav-links a[href*="cart.php"]:hover {
  background: rgba(200, 180, 150, 0.25);
  color: #8B7355;
}

/* Responsive Navbar */
@media (max-width: 900px) {
  .nav-container {
    flex-direction: column;
    align-items: flex-start;
    padding: 15px 20px;
  }
  .nav-links {
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    gap: 10px;
    margin-top: 10px;
  }
  .nav-links a {
    font-size: 0.95rem;
  }
  .btn {
    font-size: 0.9rem;
  }
}
</style>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-container">
    <!-- Logo Section -->
    <a href="/kscandles/index.php" class="logo-container">
      <img src="uploads/logo/logo.png" alt="Craft Collection Logo" class="logo-img">
      <span class="logo-text">Craft Collection</span>
    </a>

    <!-- Navigation Links -->
    <div class="nav-links">
      <a href="/kscandles/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
      <a href="/kscandles/about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a>
      <a href="/kscandles/Shop.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Shop.php' ? 'active' : '' ?>">Shop</a>
      <a href="/kscandles/products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">Products</a>
      <a href="/kscandles/contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a>
      <a href="admin/login.php" class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">Admin</a>
      <a href="/kscandles/cart.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">🛒 Cart (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)</a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/kscandles/profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">My Profile</a>
        <a href="/kscandles/orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">My Orders</a>
        <a href="/kscandles/logout.php" class="btn logout-btn">Logout</a>
      <?php else: ?>
        <a href="/kscandles/login.php" class="btn login-btn <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">Login</a>
        <a href="/kscandles/register.php" class="btn register-btn <?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
