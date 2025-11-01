<?php
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/db.php';

if (!isset($_GET['order_id'])) {
    echo "<p>Invalid order.</p>";
    require __DIR__ . '/includes/footer.php';
    exit;
}

$order_id = (int)$_GET['order_id'];

// --- Fetch order total ---
$res = $conn->query("SELECT total FROM orders WHERE id=$order_id");
$amt = $res->fetch_assoc()['total'] ?? 0;

// --- Fetch Admin QR Details ---
$qrRes = $conn->query("SELECT * FROM admin_settings LIMIT 1");
$qrData = $qrRes->fetch_assoc();
$qrImage = !empty($qrData['qr_image']) ? str_replace('../', '', $qrData['qr_image']) : 'assets/qr-upi.png';
$upiId = !empty($qrData['upi_id']) ? $qrData['upi_id'] : 'yourupi@okaxis';

// --- Handle payment proof submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utr = trim($_POST['utr_number']);
    $proof = '';

    if (!empty($_FILES['payment_proof']['name'])) {
        $targetDir = "uploads/payments/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $proof = $targetDir . time() . '_' . basename($_FILES["payment_proof"]["name"]);
        move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $proof);
    }

    $stmt = $conn->prepare("UPDATE orders SET utr_number=?, payment_proof=?, payment_status='Pending' WHERE id=?");
    $stmt->bind_param("ssi", $utr, $proof, $order_id);
    $stmt->execute();
    $stmt->close();

    echo "<p style='text-align:center;font-size:1.2rem;color:green;font-weight:600;margin-top:30px;'>✅ Thank you! Your payment is under review. Admin will verify soon.</p>";
    echo "<div style='text-align:center;margin-top:20px;'><a href='index.php' class='btn-home'>🏠 Back to Home</a></div>";
    require __DIR__ . '/includes/footer.php';
    exit;
}
?>


<style>



/* ===== Global Reset ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

/* ===== Body ===== */
body {
  background: linear-gradient(135deg, #f5eee6, #f8f4ef);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #2d2424;
}

/* ===== Wrapper ===== */
.payment-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  padding: 40px 20px;
}

/* ===== Payment Card ===== */
.payment-card {
  background: #fffdf9;
  padding: 40px 45px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(45, 36, 36, 0.15);
  width: 100%;
  max-width: 550px;
  text-align: center;
  transition: all 0.3s ease;
}

.payment-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 14px 40px rgba(0, 0, 0, 0.18);
}

/* ===== Heading ===== */
.payment-card h2 {
  background: linear-gradient(90deg, #8b7355, #c4b5a0);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-size: 1.9rem;
  font-weight: 700;
  margin-bottom: 25px;
}

/* ===== Order Info ===== */
.order-info {
  background: #f9f6f0;
  border-radius: 12px;
  padding: 15px 20px;
  margin-bottom: 25px;
  color: #4a3f35;
  font-weight: 500;
  border: 1px solid rgba(0, 0, 0, 0.05);
}

/* ===== QR Section ===== */
.qr-section {
  margin-bottom: 25px;
}

.qr-section img {
  width: 220px;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.upi-id {
  margin-top: 12px;
  font-weight: 600;
  color: #3d3227;
}

/* ===== Form ===== */
.payment-form {
  text-align: left;
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.payment-form label {
  font-weight: 600;
  color: #3d3227;
  display: flex;
  flex-direction: column;
}

.payment-form input[type=text],
.payment-form input[type=file] {
  margin-top: 6px;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #c4b5a0;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fffaf5;
}

.payment-form input:focus {
  border-color: #8b7355;
  box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.2);
  outline: none;
}

/* ===== Primary Button ===== */
.btn {
  background: linear-gradient(90deg, #8b7355, #c4b5a0);
  color: #fff;
  border: none;
  border-radius: 12px;
  padding: 14px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  text-align: center;
  transition: all 0.3s ease;
}

.btn:hover {
  background: linear-gradient(90deg, #c4b5a0, #8b7355);
  box-shadow: 0 6px 25px rgba(139, 115, 85, 0.4);
  transform: translateY(-2px);
}

/* ===== Back to Home ===== */
.btn-home {
  display: inline-block;
  margin-top: 20px;
  padding: 12px 25px;
  border-radius: 12px;
  background: #f7f3ec;
  color: #2d2424;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-home:hover {
  background: linear-gradient(135deg, #8b7355, #c4b5a0);
  color: #fff;
  transform: translateY(-2px);
}

/* ===== Secure Note ===== */
.secure-note {
  font-size: 0.9rem;
  color: #5c5045;
  margin-top: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.secure-note i {
  color: #8b7355;
}

/* ===== Responsive ===== */
@media (max-width: 600px) {
  .payment-card { padding: 25px 20px; }
  .qr-section img { width: 180px; }
  .payment-card h2 { font-size: 1.6rem; }
}

</style>

<div class="payment-wrapper">
  <div class="payment-card">
    <h2>Complete Your Payment</h2>

    <div class="order-info">
      <p><strong>Order ID:</strong> #<?= $order_id ?></p>
      <p><strong>Amount:</strong> ₹<?= number_format($amt, 2) ?></p>
    </div>

    <div class="qr-section">
      <img src="<?= htmlspecialchars($qrImage) ?>" alt="UPI QR Code">
      <p class="upi-id"><strong>UPI ID:</strong> <?= htmlspecialchars($upiId) ?></p>
    </div>

    <form method="post" enctype="multipart/form-data" class="payment-form">
      <label>Enter UTR / Transaction ID
        <input type="text" name="utr_number" placeholder="Enter your UTR / Transaction ID" required>
      </label>

      <label>Upload Payment Screenshot
        <input type="file" name="payment_proof" accept="image/*" required>
      </label>

      <button class="btn" type="submit">Submit Payment Details</button>
    </form>

    <a href="index.php" class="btn-home">🏠 Back to Home</a>

    <div class="secure-note">
      <i class="fas fa-lock"></i> Your payment details are securely processed.
    </div>
  </div>
</div>



<?php require __DIR__ . '/includes/footer.php'; ?>
