<?php
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { echo "Invalid product."; require __DIR__ . '/includes/footer.php'; exit; }

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i",$id); $stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) { echo "Not found."; require __DIR__ . '/includes/footer.php'; exit; }

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {
    $name = trim($_POST['name']) ?: 'Anonymous';
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'] ?? null;

    $ins = $conn->prepare("INSERT INTO reviews (product_id, user_id, name, rating, comment) VALUES (?,?,?,?,?)");
    $ins->bind_param("iisss", $id, $user_id, $name, $rating, $comment);
    $ins->execute();
    $ins->close();

    $agg = $conn->query("SELECT AVG(rating) AS avg_r, COUNT(*) AS cnt FROM reviews WHERE product_id = $id")->fetch_assoc();
    $avg = round($agg['avg_r'],2); $cnt = (int)$agg['cnt'];
    $conn->query("UPDATE products SET avg_rating = $avg, total_reviews = $cnt WHERE id = $id");

    redirect("/kscandles/product_detail.php?id=$id#reviews");
}

$revStmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
$revStmt->bind_param("i",$id); $revStmt->execute();
$reviews = $revStmt->get_result();
?>


<style>
/* ======= PRODUCT DETAIL PAGE ======= */
/* ===========================
   GLOBAL THEME — KSCandles
   =========================== */
body {
  background: linear-gradient(135deg, #fbe8d3, #f8d1b0, #f6b26b);
  background-attachment: fixed;
  font-family: "Poppins", sans-serif;
  margin: 0;
  padding: 0;
  color: #3e2a1d;
}

/* ===========================
   PRODUCT DETAIL PAGE
   =========================== */
.product-detail-container {
  max-width: 1200px;
  margin: 60px auto;
  padding: 0 20px;
}

/* ===== PRODUCT CARD ===== */
.product-detail {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  background: #fffaf5;
  border-radius: 22px;
  box-shadow: 0 6px 25px rgba(180, 115, 70, 0.15);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  margin-bottom: 50px;
}

.product-detail:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 35px rgba(180, 115, 70, 0.25);
}

/* ===== PRODUCT IMAGE ===== */
.product-img {
  width: 45%;
  height: 420px;
  object-fit: cover;
  border-radius: 22px 0 0 22px;
}

/* ===== PRODUCT INFO ===== */
.product-info {
  flex: 1;
  padding: 35px;
}

.product-info h1 {
  font-size: 2rem;
  color: #3e2a1d;
  margin-bottom: 12px;
}

.product-info .desc {
  color: #6b4e3d;
  line-height: 1.4;
  margin-bottom: 20px;
}

.product-info .price {
  font-size: 1.6rem;
  font-weight: 700;
  color: #d68132;
  margin-bottom: 10px;
}

.product-info .rating {
  color: #f5a623;
  font-weight: 600;
  margin-bottom: 20px;
}

/* ===== ADD TO CART FORM ===== */
.cart-form {
  display: flex;
  align-items: center;
  gap: 12px;
}

.qty-input {
  width: 70px;
  padding: 8px;
  border: 1px solid #d5b899;
  border-radius: 10px;
  font-size: 1rem;
  text-align: center;
  color: #3e2a1d;
}

.btn {
  background: linear-gradient(135deg, #eab676, #e0853f);
  border: none;
  color: #fff;
  font-weight: 600;
  padding: 11px 18px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(135deg, #e0853f, #d66d0a);
  box-shadow: 0 4px 15px rgba(224, 133, 63, 0.35);
  transform: translateY(-2px);
}

/* ===========================
   REVIEWS SECTION
   =========================== */
.reviews-section {
  background: #fffaf5;
  border-radius: 22px;
  box-shadow: 0 6px 25px rgba(180, 115, 70, 0.12);
  padding: 35px;
}

.reviews-section h2 {
  font-size: 1.7rem;
  color: #3e2a1d;
  margin-bottom: 20px;
}

.no-reviews {
  color: #7b6757;
  font-style: italic;
  margin-bottom: 25px;
}

/* ===== REVIEW CARD ===== */
.review-card {
  border-bottom: 1px solid #f0e4d6;
  padding: 15px 0;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.review-header strong {
  font-size: 1rem;
  color: #3e2a1d;
}

.stars {
  color: #f5a623;
  font-size: 1.1rem;
}

.review-text {
  color: #6b4e3d;
  margin: 8px 0;
}

.review-date {
  color: #a08b79;
  font-size: 0.85rem;
}

/* ===== REVIEW FORM ===== */
.review-form {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.review-form input,
.review-form select,
.review-form textarea {
  padding: 10px;
  border: 1px solid #e3c8aa;
  border-radius: 10px;
  font-family: "Poppins", sans-serif;
  font-size: 0.95rem;
  width: 100%;
  color: #3e2a1d;
  background: #fff;
  transition: 0.3s ease;
}

.review-form input:focus,
.review-form select:focus,
.review-form textarea:focus {
  border-color: #e0853f;
  box-shadow: 0 0 6px rgba(224, 133, 63, 0.3);
}

.review-form textarea {
  resize: none;
}

.review-form button {
  align-self: flex-start;
}

/* ===========================
   RESPONSIVE DESIGN
   =========================== */
@media (max-width: 768px) {
  .product-detail {
    flex-direction: column;
  }

  .product-img {
    width: 100%;
    height: 300px;
    border-radius: 22px 22px 0 0;
  }

  .product-info {
    padding: 25px;
  }

  .reviews-section {
    padding: 25px;
  }
}

</style>

<main class="product-detail-container">
  <article class="product-detail">
    <img src="/kscandles/uploads/products/<?= htmlspecialchars($product['image'] ?: 'placeholder.png') ?>" alt="Product Image" class="product-img">
    <div class="product-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <p class="desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <p class="price">₹ <?= number_format($product['price'],2) ?></p>
      <p class="rating">⭐ <?= $product['avg_rating'] ? number_format($product['avg_rating'],1) : '—' ?> (<?= (int)$product['total_reviews'] ?> reviews)</p>

      <form method="post" action="/kscandles/cart.php" class="cart-form">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="number" name="quantity" value="1" min="1" class="qty-input">
        <button type="submit" name="add_to_cart" class="btn">🛒 Add to Cart</button>
      </form>
    </div>
  </article>

  <section id="reviews" class="reviews-section">
    <h2>Customer Reviews</h2>
    <?php if ($reviews->num_rows === 0): ?>
      <p class="no-reviews">No reviews yet. Be the first to review!</p>
    <?php else: ?>
      <?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="review-card">
          <div class="review-header">
            <strong><?= htmlspecialchars($r['name']) ?></strong>
            <span class="stars"><?= str_repeat('★',$r['rating']).str_repeat('☆',5-$r['rating']) ?></span>
          </div>
          <p class="review-text"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
          <small class="review-date"><?= htmlspecialchars($r['created_at']) ?></small>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>

    <h3>Write a Review</h3>
    <form method="post" class="review-form">
      <input type="text" name="name" placeholder="Your name (optional)">
      <label>Rating
        <select name="rating" required>
          <option value="5">5 — Excellent</option>
          <option value="4">4 — Very Good</option>
          <option value="3">3 — Good</option>
          <option value="2">2 — Fair</option>
          <option value="1">1 — Poor</option>
        </select>
      </label>
      <textarea name="comment" rows="4" required placeholder="Write your review..."></textarea>
      <button name="review_submit" class="btn" type="submit">Submit Review</button>
    </form>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>


