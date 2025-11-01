<?php
include 'includes/navbar.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php';

$status = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_POST['tab'] ?? 'General Inquiry';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if ($name && $email && $message && $phone) {
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            $status = ['type' => 'warning', 'msg' => 'Please enter a valid contact number (10–15 digits only).'];
        } else {
            // ✅ Save message into database
            $stmt = $conn->prepare("
                INSERT INTO contact_messages (name, email, phone, subject, message, sent_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("sssss", $name, $email, $phone, $tab, $message);
            $stmt->execute();

            $status = ['type' => 'success', 'msg' => 'Thank you! Your message has been saved successfully.'];
        }
    } else {
        $status = ['type' => 'warning', 'msg' => 'Please fill out all fields.'];
    }
}
?>

<style>
/* ====== KS Candles Contact Page (Simplified) ====== */
body {
  /*
  font-family: 'Poppins', sans-serif;*/
  background: #fffaf7;
  color: #333;
}
.contact-container {
  max-width: 700px;
  margin: 80px auto;
  background: #fff;
  border-radius: 20px;
  padding: 45px 55px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  animation: fadeIn 0.5s ease;
  text-align: center;
}
.contact-container h1 {
  font-family: 'Cormorant Garamond', serif;
  color: #3B2F2F;
  font-size: 2.4rem;
}
.subtitle {
  color: #6F6252;
  font-size: 1.05rem;
  margin-bottom: 30px;
}
.tab-buttons {
  display: flex;
  justify-content: space-between;
  background: #f6e6dc;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 25px;
}
.tab-buttons button {
  flex: 1;
  padding: 12px 0;
  border: none;
  background: transparent;
  font-weight: 600;
  color: #555;
  cursor: pointer;
  transition: 0.3s;
}
.tab-buttons button.active {
  background: #e0bfa3;
  color: #fff;
}
form {
  display: none;
  text-align: left;
  animation: fadeIn 0.3s ease;
}
form.active { display: block; }
label {
  display: block;
  margin-top: 14px;
  color: #4A3C30;
  font-weight: 600;
}
input, textarea {
  width: 100%;
  padding: 12px 14px;
  margin-top: 6px;
  border: 1px solid #DCCFC0;
  border-radius: 10px;
  background: #FAF8F5;
  transition: 0.3s;
}
input:focus, textarea:focus {
  border-color: #C58940;
  background: #fff;
  outline: none;
  box-shadow: 0 0 8px rgba(197, 137, 64, 0.3);
}
textarea { resize: none; height: 120px; }
.btn {
  width: 100%;
  background: linear-gradient(135deg, #8B7355, #C4B5A0);
  color: #fff;
  border: none;
  border-radius: 12px;
  margin-top: 25px;
  padding: 14px;
  font-weight: 600;
  font-size: 1.2rem;
  cursor: pointer;
  transition: 0.3s;
}
.btn:hover {
  background: linear-gradient(135deg, #A68A64, #8B7355);
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(139,115,85,0.25);
}
.alert {
  padding: 12px 15px;
  border-radius: 10px;
  margin-bottom: 20px;
  font-weight: 500;
  text-align: center;
}
.alert.success { background: #EAF9E7; color: #2E6B2E; border: 1px solid #A9E3A2; }
.alert.warning { background: #FFF5E6; color: #8A4B05; border: 1px solid #F1C27D; }
@media (max-width: 768px) {
  .contact-container { margin: 30px 15px; padding: 30px 25px; }
  .tab-buttons button { font-size: 13px; padding: 10px 0; }
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="contact-container">
  <h1>Contact Us</h1>
  <p class="subtitle">We’d love to hear from you. Choose your query type below.</p>

  <?php if (!empty($status)): ?>
    <div class="alert <?= $status['type'] ?>"><?= htmlspecialchars($status['msg']) ?></div>
  <?php endif; ?>

  <!-- Tabs -->
  <div class="tab-buttons">
    <button class="tab-btn active" data-tab="general">General Inquiry</button>
    <button class="tab-btn" data-tab="custom">Custom Order</button>
    <button class="tab-btn" data-tab="workshop">Workshop Info</button>
  </div>

  <!-- General Inquiry -->
  <form id="general" class="tab-form active" method="POST">
    <input type="hidden" name="tab" value="General Inquiry">
    <label>Full Name</label>
    <input type="text" name="name" required>
    <label>Email Address</label>
    <input type="email" name="email" required>
    <label>Contact Number</label>
    <input type="tel" name="phone" pattern="[0-9]{10,15}" required>
    <label>Message</label>
    <textarea name="message" required></textarea>
    <button type="submit" class="btn">Send Message</button>
  </form>

  <!-- Custom Order -->
  <form id="custom" class="tab-form" method="POST">
    <input type="hidden" name="tab" value="Custom Order">
    <label>Full Name</label>
    <input type="text" name="name" required>
    <label>Email Address</label>
    <input type="email" name="email" required>
    <label>Contact Number</label>
    <input type="tel" name="phone" pattern="[0-9]{10,15}" required>
    <label>Custom Order Details</label>
    <textarea name="message" required></textarea>
    <button type="submit" class="btn">Send Request</button>
  </form>

  <!-- Workshop Info -->
  <form id="workshop" class="tab-form" method="POST">
    <input type="hidden" name="tab" value="Workshop Info">
    <label>Full Name</label>
    <input type="text" name="name" required>
    <label>Email Address</label>
    <input type="email" name="email" required>
    <label>Contact Number</label>
    <input type="tel" name="phone" pattern="[0-9]{10,15}" required>
    <label>Message / Questions</label>
    <textarea name="message" required></textarea>
    <button type="submit" class="btn">Request Info</button>
  </form>
</div>

<script>
const tabButtons = document.querySelectorAll(".tab-btn");
const forms = document.querySelectorAll(".tab-form");
tabButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    tabButtons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    const tab = btn.dataset.tab;
    forms.forEach(f => f.classList.toggle("active", f.id === tab));
  });
});
</script>

<?php include 'includes/footer.php'; ?>
