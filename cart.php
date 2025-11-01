<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/navbar.php';

// Handle cart operations regardless of login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $id = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);
    $_SESSION['cart'][$id] = $qty;

    // ✅ Record activity if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("
            INSERT INTO cart_activity (user_id, product_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                quantity = VALUES(quantity), 
                added_at = CURRENT_TIMESTAMP
        ");
        $stmt->bind_param("iii", $user_id, $id, $qty);
        $stmt->execute();
    }

    redirect('/kscandles/cart.php');
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);

    // ✅ Optional: also remove from DB for logged-in users
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $del = $conn->prepare("DELETE FROM cart_activity WHERE user_id = ? AND product_id = ?");
        $del->bind_param("ii", $user_id, $id);
        $del->execute();
    }

    redirect('/kscandles/cart.php');
}
// ✅ Cleanup any abandoned cart record if user completed order
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $conn->query("DELETE FROM abandoned_carts WHERE user_id = $uid");
}


// Fetch cart items
$items = [];
$subtotal = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("SELECT id,name,price,image FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $r['qty'] = $_SESSION['cart'][$r['id']];
        $r['line'] = $r['qty'] * $r['price'];
        $subtotal += $r['line'];
        $items[] = $r;
    }
}

// Totals
$shipping = ($subtotal >= 799 || empty($items)) ? 0 : 149;
$grandTotal = $subtotal + $shipping;
$isLoggedIn = isset($_SESSION['user_id']);
?>


<style>
/* ======================================
   KSCandles - Cart Page Styles
   Unified Handcrafted Theme
   ====================================== */

:root {
  --primary: #8b6d5c;            /* warm candle brown */
  --accent: #c4b5a0;             /* beige gold */
  --bg-light: #faf8f6;           /* soft candle tone */
  --text-dark: #2d2424;
  --text-mid: #555;
  --white: #ffffff;
  --gradient: linear-gradient(135deg, #a07448, #d8b78a);
  --danger: linear-gradient(135deg, #f85032, #e73827);
  --soft-shadow: 0 5px 15px rgba(0,0,0,0.1);
  --radius: 12px;
  --transition: all 0.3s ease;
}

/* ===== Page Base ===== */
body {
  /*
  font-family: "Poppins", sans-serif;*/
  background: var(--bg-light);
  color: var(--text-dark);
  margin: 0;
  padding: 0;
}

/* ===== Heading ===== */
h1 {
  text-align: center;
  margin: 40px 0 30px;
  font-size: 2.3rem;
  font-family: "Cormorant Garamond", serif;
  font-weight: 700;
  background: var(--gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: 1px;
}

/* ===== Empty Cart ===== */
.empty {
  text-align: center;
  margin: 60px auto;
  font-size: 1.2rem;
  color: var(--text-mid);
}
.empty a {
  color: var(--primary);
  font-weight: 600;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: var(--transition);
}
.empty a:hover {
  border-bottom: 1px solid var(--accent);
}

/* ===== Cart Container ===== */
.cart-container {
  display: flex;
  justify-content: space-around;
  align-items: flex-start;
  flex-wrap: wrap;
  width: 90%;
  margin: 0 auto 70px;
  gap: 30px;
}

/* ===== Cart Items ===== */
.cart-items {
  flex: 2;
  min-width: 600px;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.cart-item-box {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: var(--soft-shadow);
  padding: 15px 20px;
  transition: var(--transition);
}
.cart-item-box:hover {
  transform: scale(1.01);
}
.item-info {
  display: flex;
  align-items: center;
  gap: 15px;
}
.item-info img {
  width: 70px;
  height: 70px;
  border-radius: 10px;
  object-fit: cover;
  border: 1px solid #eee;
}
.item-info h4 {
  margin: 0;
  font-size: 1rem;
  color: var(--text-dark);
}
.line-price {
  font-weight: 600;
  color: var(--primary);
}

/* ===== Quantity Control ===== */
.qty-box {
  display: flex;
  align-items: center;
  gap: 8px;
}
.qty-form {
  display: flex;
  align-items: center;
  gap: 6px;
}
.qty-form input[type=number] {
  width: 42px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: #fafafa;
  font-weight: bold;
  color: var(--text-dark);
}
.qty-btn {
  background: #f0f0f0;
  border: none;
  padding: 6px 10px;
  border-radius: 50%;
  cursor: pointer;
  font-weight: bold;
  transition: var(--transition);
}
.qty-btn:hover {
  background: var(--accent);
  color: var(--white);
}
.btn.remove {
  background: var(--danger);
  color: var(--white);
  padding: 7px 14px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: var(--transition);
}
.btn.remove:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* ===== Bill Summary ===== */
.bill-summary {
  flex: 1;
  min-width: 280px;
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: var(--soft-shadow);
  padding: 22px;
  height: fit-content;
  position: sticky;
  top: 100px;
}
.bill-summary h3 {
  text-align: center;
  margin-bottom: 25px;
  color: var(--primary);
  font-family: "Cormorant Garamond", serif;
}

/* Bill Items with Product Image */
.bill-item-with-img {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 14px;
}
.bill-item-with-img img {
  width: 45px;
  height: 45px;
  border-radius: 8px;
  object-fit: cover;
  border: 1px solid #eee;
}
.bill-item-details {
  flex: 1;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.bill-name {
  font-size: 0.9rem;
  color: var(--text-mid);
}
.bill-price {
  font-weight: 600;
  color: var(--primary);
}

/* Totals */
.bill-item {
  display: flex;
  justify-content: space-between;
  margin: 8px 0;
  color: var(--text-dark);
}
.bill-total {
  display: flex;
  justify-content: space-between;
  font-size: 1.2rem;
  margin-top: 15px;
  color: var(--text-dark);
  font-weight: 600;
}

/* Checkout / Login Buttons */
.btn.checkout,
.btn.login {
  display: block;
  width: 100%;
  text-align: center;
  padding: 12px;
  border-radius: var(--radius);
  text-decoration: none;
  color: var(--white);
  font-weight: 600;
  margin-top: 20px;
  transition: var(--transition);
}
.btn.checkout {
  background: var(--gradient);
}
.btn.checkout:hover {
  background: linear-gradient(135deg, #8b6d5c, #c4b5a0);
  transform: translateY(-2px);
}
.btn.login {
  background: linear-gradient(135deg, #ff9966, #ff5e62);
}
.btn.login:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
  .cart-container {
    flex-direction: column;
    align-items: center;
  }
  .cart-items,
  .bill-summary {
    min-width: 95%;
  }
  .cart-item-box {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  .bill-item-with-img img {
    width: 40px;
    height: 40px;
  }
  h1 {
    font-size: 1.8rem;
  }
}

</style>

<h1>Your Shopping Cart</h1>

<?php if (empty($items)): ?>
  <p class="empty">🛒 Your cart is empty. <a href="/kscandles/shop.php">Continue Shopping</a></p>
<?php else: ?>
  <div class="cart-container">
    <div class="cart-items">
      <?php foreach ($items as $it): ?>
        <div class="cart-item-box">
          <div class="item-info">
            <img src="/kscandles/uploads/products/<?= htmlspecialchars($it['image']) ?>" alt="<?= htmlspecialchars($it['name']) ?>">
            <div>
              <h4><?= htmlspecialchars($it['name']) ?></h4>
              <p>₹<?= number_format($it['price'],2) ?></p>
            </div>
          </div>
          <div class="qty-box">
            <form method="post" class="qty-form">
              <input type="hidden" name="product_id" value="<?= $it['id'] ?>">
              <button type="button" class="qty-btn minus">−</button>
              <input type="number" name="quantity" value="<?= $it['qty'] ?>" min="1" readonly>
              <button type="button" class="qty-btn plus">+</button>
            </form>
          </div>
          <div class="line-price">₹<?= number_format($it['line'],2) ?></div>
          <a href="?remove=<?= $it['id'] ?>" class="btn remove">Remove</a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="bill-summary">
      <h3>🧾 Order Summary</h3>
      <?php foreach ($items as $it): ?>
        <div class="bill-item-with-img">
          <img src="/kscandles/uploads/products/<?= htmlspecialchars($it['image']) ?>" alt="<?= htmlspecialchars($it['name']) ?>">
          <div class="bill-item-details">
            <span class="bill-name"><?= htmlspecialchars($it['name']) ?> (x<?= $it['qty'] ?>)</span>
            <span class="bill-price">₹<?= number_format($it['line'],2) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
      <hr>
      <div class="bill-item"><strong>Subtotal</strong><strong>₹<?= number_format($subtotal,2) ?></strong></div>
      <div class="bill-item"><strong>Shipping</strong><strong><?= $shipping == 0 ? 'Free' : '₹'.number_format($shipping,2) ?></strong></div>
      <hr>
      <div class="bill-total"><strong>Total</strong><strong>₹<?= number_format($grandTotal,2) ?></strong></div>

      <?php if ($isLoggedIn): ?>
        <a class="btn checkout" href="/kscandles/checkout.php">Proceed to Checkout</a>
      <?php else: ?>
        <a class="btn login" href="/kscandles/login.php">Login to Checkout</a>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
document.querySelectorAll('.qty-form').forEach(form => {
  const minus = form.querySelector('.minus');
  const plus = form.querySelector('.plus');
  const qty = form.querySelector('input[name="quantity"]');
  minus.addEventListener('click', () => {
    if (qty.value > 1) {
      qty.value--;
      form.submit();
    }
  });
  plus.addEventListener('click', () => {
    qty.value++;
    form.submit();
  });
});
</script>


