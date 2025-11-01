<?php
include 'includes/navbar.php'; 
require_once __DIR__ . '/includes/functions.php';

// Initialize session for cart
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart array if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart action
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 1; // default quantity = 1
    } else {
        $_SESSION['cart'][$productId]++; // increment quantity
    }
    header("Location: shop.php?added=$productId");
    exit;
}

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';

$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = "p.name LIKE ?";
    $params[] = "%$search%";
    $types .= 's';
}
if ($category !== '') {
    $where[] = "c.id = ?";
    $params[] = (int)$category;
    $types .= 'i';
}
if ($min !== '') {
    $where[] = "p.price >= ?";
    $params[] = (float)$min;
    $types .= 'd';
}
if ($max !== '') {
    $where[] = "p.price <= ?";
    $params[] = (float)$max;
    $types .= 'd';
}

$sql = "SELECT p.*, c.name AS category_name,
        (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) AS avg_rating
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id";

if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$catRes = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$cats = $catRes->fetch_all(MYSQLI_ASSOC);
?>

<h1>Shop</h1>

<!-- Success message -->
<?php if (isset($_GET['added'])): ?>
<div class="cart-notice">
  ✅ Product added to cart!
</div>
<?php endif; ?>



<style>
/* ======== Shop Page Styling ======== */
/* ================================
   KSCANDLES SHOP PAGE THEME
   Elegant handcrafted candle aesthetic
================================ */

/* Background + Typography */
body {
  background: linear-gradient(135deg, #faf7f2, #f5f1ea);
 /* font-family: "Poppins", sans-serif;*/
  color: #2d2424;
  margin: 0;
  padding: 0;
}

/* Page Heading */
h1 {
  text-align: center;
  margin: 40px 0 30px;
   
  font-family: "Cormorant Garamond", serif;
  font-size: 2.4rem;
  color: #8b7355;
  letter-spacing: 1px;
  position: relative;
}

h1::after {
  content: "";
  display: block;
  width: 60px;
  height: 3px;
  background: #c4b5a0;
  margin: 10px auto 0;
  border-radius: 5px;
}

/* Success (Add to Cart) Notice */
.cart-notice {
  text-align: center;
  background: linear-gradient(135deg, #8b7355, #c4b5a0);
  color: #fff;
  width: fit-content;
  margin: 0 auto 20px;
  padding: 12px 25px;
  border-radius: 8px;
  font-weight: 600;
  animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ===========================
   Filter Bar
=========================== */
.search-filter {
  max-width: 900px;
  margin: 0 auto 50px;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 15px;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  padding: 20px 25px;
  border-radius: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  border: 1px solid rgba(200, 180, 150, 0.2);
}

.search-filter input,
.search-filter select {
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #d6cfc2;
  outline: none;
  min-width: 150px;
  font-size: 0.95rem;
  background: #fff;
  transition: 0.3s;
}

.search-filter input:focus,
.search-filter select:focus {
  border-color: #8b7355;
  box-shadow: 0 0 6px rgba(139, 115, 85, 0.3);
}

/* Filter Button */
.search-filter .btn {
  background: linear-gradient(135deg, #c4b5a0, #8b7355);
  border: none;
  padding: 10px 18px;
  border-radius: 10px;
  color: #fff;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

.search-filter .btn:hover {
  background: linear-gradient(135deg, #8b7355, #c4b5a0);
  transform: translateY(-2px);
}

/* ===========================
   Product Grid
=========================== */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  max-width: 1200px;
  margin: 0 auto 60px;
  padding: 0 20px;
}

/* ===========================
   Product Card
=========================== */
.product-card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.06);
  transition: all 0.3s ease;
  text-align: center;
  padding-bottom: 20px;
  border: 1px solid rgba(200, 180, 150, 0.15);
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.product-card img {
  width: 100%;
  height: 230px;
  object-fit: cover;
  border-top-left-radius: 18px;
  border-top-right-radius: 18px;
}

/* Product Info */
.product-card h3 {
  margin: 15px 0 5px;
  font-size: 1.2rem;
  color: #2d2424;
  font-weight: 600;
}

.product-card p {
  margin: 4px 0;
  font-size: 0.95rem;
  color: #6b5f4a;
}

/* ===========================
   Buttons (View / Cart)
=========================== */
.btn-group {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 10px;
}

.product-card .btn {
  display: inline-block;
  background: linear-gradient(135deg, #8B7355, #8b7355);
  color: #fff;
  padding: 8px 14px;
  border-radius: 10px;
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: 500;
}

.product-card .btn:hover {
  background: linear-gradient(135deg, #8b7355, #c4b5a0);
  transform: translateY(-3px);
}

/* ===========================
   Responsive
=========================== */
@media (max-width: 768px) {
  .search-filter {
    flex-direction: column;
    align-items: center;
  }
  .search-filter input,
  .search-filter select {
    width: 100%;
  }
  .product-card img {
    height: 180px;
  }
}

</style>

<form method="GET" class="search-filter">
  <input name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
  <select name="category">
    <option value="">All Categories</option>
    <?php foreach ($cats as $c): ?>
      <option value="<?= $c['id'] ?>" <?= ($category == $c['id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <input name="min" placeholder="Min price" value="<?= htmlspecialchars($min) ?>">
  <input name="max" placeholder="Max price" value="<?= htmlspecialchars($max) ?>">
  <button class="btn">Filter</button>
</form>

<section class="product-grid">
  <?php if ($res->num_rows === 0): ?>
    <p>No products found.</p>
  <?php else: while ($p = $res->fetch_assoc()): ?>
    <?php 
      // Properly handle image path
      $imgPath = !empty($p['image']) 
          ? 'uploads/products/' . htmlspecialchars($p['image'])
          : 'uploads/products/placeholder.png';
    ?>
    <article class="product-card">
      <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($p['name']) ?>">
      <h3><?= htmlspecialchars($p['name']) ?></h3>
      <p>Category: <?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></p>
      <p>₹ <?= number_format($p['price'], 2) ?></p>
      <p>Rating: <?= $p['avg_rating'] ? number_format($p['avg_rating'], 1) : '—' ?></p>

      <div class="btn-group">
        <a class="btn view-btn" href="/kscandles/product_detail.php?id=<?= $p['id'] ?>">View Details</a>
        <a class="btn cart-btn" href="shop.php?action=add&id=<?= $p['id'] ?>">🛒 Add to Cart</a>
      </div>
    </article>
  <?php endwhile; endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>


