<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/navbar.php';

// Fetch all products with categories and average rating
$sql = "
    SELECT p.*, c.name AS category_name,
           (SELECT ROUND(AVG(rating),1) FROM reviews r WHERE r.product_id = p.id) AS avg_rating
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
";
$products = $conn->query($sql);
?>


<style>
/* ======== Modern Grid Product Page ======== */
/* ===================================================
   KSCandles – Product Catalog Page Styling
   Theme: Elegant Handmade Candle Store
   =================================================== */

/* ======= Global Reset & Typography ======= */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Inter:wght@400;500;600&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  /*
  font-family: 'Cormorant Garamond', serif;
 /* font-family: 'Inter', sans-serif;*/
  color: #2D2424;
  background: #FAF8F5;
  overflow-x: hidden;
  line-height: 1.4;
}

/* Headings */
h1, h2, h3, h4, h5, h6 {
  font-family: 'Cormorant Garamond', serif;
  font-weight: 600;
  color: #3b2f2f;
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* ======= Catalog Layout ======= */
.catalog-container {
  max-width: 1300px;
  margin: 50px auto;
  padding: 0 20px;
  animation: fadeIn 0.6s ease-out;
}

.catalog-container h1 {
  text-align: center;
  font-size: 2.6rem;
  color: #3B2F2F;
  margin-bottom: 45px;
  letter-spacing: 1px;
  position: relative;
}

.catalog-container h1::after {
  content: "";
  display: block;
  width: 80px;
  height: 3px;
  background: linear-gradient(135deg, #C4B5A0, #8B7355);
  margin: 12px auto 0;
  border-radius: 2px;
}

/* ======= Product Grid ======= */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
}

/* ======= Product Card ======= */
.product-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.08);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: all 0.35s ease;
  cursor: pointer;
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 14px 28px rgba(0,0,0,0.12);
}

/* Product Image */
.product-img {
  width: 100%;
  height: 260px;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.product-card:hover .product-img {
  transform: scale(1.05);
}

/* Product Info */
.product-info {
  padding: 20px 22px 25px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.product-info h2 {
  font-size: 1.25rem;
  color: #2D2424;
  margin-bottom: 8px;
}

.category {
  color: #746A5A;
  font-size: 0.9rem;
  margin-bottom: 6px;
}

.price {
  font-size: 1.15rem;
  font-weight: 700;
  color: #c58940ff;
  margin-bottom: 8px;
}

.rating {
  color: #ebaf6fff;
  font-weight: 500;
  font-size: 0.95rem;
  margin-bottom: 14px;
}

/* View Details Button */
.btn {
  display: inline-block;
  text-align: center;
  background: linear-gradient(135deg, #8B7355, #8b7355);;
  color: #fff;
  text-decoration: none;
  padding: 10px 0;
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(135deg, #A68A64, #8B7355);
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(139, 115, 85, 0.2);
}

/* ======= Empty State ======= */
.no-products {
  text-align: center;
  font-style: italic;
  color: #999;
  font-size: 1.1rem;
  margin-top: 40px;
}

/* ======= Scrollbar ======= */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #F5F1ED;
}

::-webkit-scrollbar-thumb {
  background: #C4B5A0;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: #8B7355;
}

/* ======= Animations ======= */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ======= Responsive ======= */
@media (max-width: 768px) {
  .catalog-container h1 {
    font-size: 2rem;
  }
  .product-img {
    height: 220px;
  }
  .product-info h2 {
    font-size: 1.1rem;
  }
}

</style>

<main class="catalog-container">
  <h1>All Products & Reviews</h1>

  <?php if ($products->num_rows === 0): ?>
    <p class="no-products">No products available right now.</p>
  <?php else: ?>
    <div class="product-grid">
      <?php while ($p = $products->fetch_assoc()): ?>
        <div class="product-card">
          <!-- ✅ Correct Dynamic Image Path -->
          <img src="/kscandles/uploads/products/<?= htmlspecialchars($p['image'] ?: 'placeholder.png') ?>" 
               alt="<?= htmlspecialchars($p['name']) ?>" class="product-img">
          
          <div class="product-info">
            <h2><?= htmlspecialchars($p['name']) ?></h2>
            <p class="category"><b>Category:</b> <?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></p>
            <p class="price">₹ <?= number_format($p['price'], 2) ?></p>
            <p class="rating">⭐ <?= $p['avg_rating'] ? "{$p['avg_rating']}/5" : "No ratings yet" ?></p>
            <a href="/kscandles/product_detail.php?id=<?= $p['id'] ?>" class="btn">View Details</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>


