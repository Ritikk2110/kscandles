<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
include 'includes/navbar.php';

$res = getProducts($conn);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['user_name']) : '';
?>
<head>  <link rel="stylesheet" href="/kscandles/assets/css/style.css"> </head>


<!-- HERO / WELCOME SECTION -->
<section class="hero-section">
  <div class="hero-content">
    <?php if ($isLoggedIn): ?>
      <h1>Welcome back, <span class="highlight"><?= $userName ?></span> 👋</h1>
      <p class="subtext">Explore our latest handcrafted candles and manage your profile easily.</p>
    <?php else: ?>
      <p class="subtext">Discover handcrafted candles that bring warmth and light to your space.</p>
      <div class="guest-actions">
        <a href="/kscandles/register.php" class="btn primary">Create Account</a>
        <a href="/kscandles/login.php" class="btn secondary">Login</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- PRODUCT GRID -->
<section class="product-section">
  <h2 class="section-title">Our Featured Candles</h2>
  <div class="product-grid">
    <?php while ($p = $res->fetch_assoc()): ?>
      <article class="product-card">
        <img src="uploads/products/<?= htmlspecialchars($p['image'] ?: 'placeholder.jpeg') ?>" 
             alt="<?= htmlspecialchars($p['name']) ?>">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p class="price">₹ <?= number_format($p['price'], 2) ?></p>
        <p class="rating">⭐ <?= $p['avg_rating'] ? number_format($p['avg_rating'], 1) : '—' ?></p>
        <a class="btn view-btn" href="/kscandles/product_detail.php?id=<?= $p['id'] ?>">View Details</a>
      </article>
    <?php endwhile; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>


