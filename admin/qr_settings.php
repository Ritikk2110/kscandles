<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upi = trim($_POST['upi_id']);
    $qrPath = '';

    // Handle image upload
    if (!empty($_FILES['qr_image']['name'])) {
        $targetDir = "../uploads/admin_qr/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $qrPath = $targetDir . time() . '_' . basename($_FILES['qr_image']['name']);
        move_uploaded_file($_FILES['qr_image']['tmp_name'], $qrPath);
    }

    // Check if already exists
    $exists = $conn->query("SELECT id FROM admin_settings LIMIT 1")->num_rows;
    if ($exists) {
        $sql = "UPDATE admin_settings SET upi_id=?, qr_image=IF(?='', qr_image, ?) LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $upi, $qrPath, $qrPath);
    } else {
        $sql = "INSERT INTO admin_settings (upi_id, qr_image) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $upi, $qrPath);
    }
    $stmt->execute();
    $stmt->close();
    $msg = "✅ QR & UPI details updated successfully!";
}
$qr = $conn->query("SELECT * FROM admin_settings LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage UPI QR Code</title>
  <style>
   


   /* ==========================================
   KSCandles Admin — Manage UPI QR Page
   Elegant Handcrafted Brown-Gold Theme
   Inspired by Candle Haven
   ========================================== */

/* Fonts */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Poppins:wght@400;500;600&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #f7efe6, #e9dccb);
  color: #3d2f23;
  padding: 40px 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

/* Container */
.container {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border-radius: 22px;
  box-shadow: 0 8px 28px rgba(0, 0, 0, 0.15);
  padding: 40px 45px;
  max-width: 520px;
  width: 100%;
  text-align: center;
  transition: all 0.4s ease;
  border: 1px solid rgba(200, 170, 120, 0.25);
}

.container:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.18);
}

/* Title */
h2 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 2rem;
  color: #6b4f31;
  letter-spacing: 0.5px;
  margin-bottom: 25px;
  background: linear-gradient(135deg, #8b5e34, #b8925f);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Message Box */
.msg {
  margin-bottom: 20px;
  padding: 12px 14px;
  background: rgba(219, 237, 219, 0.8);
  color: #2f5d2f;
  border-left: 4px solid #a5d6a7;
  font-weight: 600;
  border-radius: 10px;
}

/* Inputs & Labels */
label {
  display: block;
  font-weight: 600;
  color: #5a4532;
  text-align: left;
  margin-top: 15px;
  margin-bottom: 6px;
  font-family: 'Poppins', sans-serif;
}

input[type="text"],
input[type="file"] {
  width: 100%;
  padding: 12px 14px;
  margin-bottom: 18px;
  border-radius: 12px;
  border: 1px solid rgba(180, 140, 80, 0.4);
  font-size: 1rem;
  background: rgba(255, 255, 255, 0.8);
  transition: all 0.3s ease;
}

input[type="text"]:focus,
input[type="file"]:focus {
  border-color: #b68b57;
  box-shadow: 0 0 0 3px rgba(182, 139, 87, 0.2);
  outline: none;
}

/* Button */
button {
  width: 100%;
  padding: 14px;
  border-radius: 12px;
  background: linear-gradient(135deg, #b68b57, #9a7444);
  color: #fffefb;
  border: none;
  cursor: pointer;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(182, 139, 87, 0.25);
}

button:hover {
  background: linear-gradient(135deg, #c9a870, #b38652);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(182, 139, 87, 0.35);
}

/* Image Preview */
img {
  width: 220px;
  margin-top: 15px;
  border-radius: 14px;
  box-shadow: 0 6px 16px rgba(0,0,0,0.1);
  border: 1px solid rgba(140,100,60,0.2);
}

/* Back Button */
.btn-home {
  display: inline-block;
  margin-top: 25px;
  padding: 12px 26px;
  border-radius: 12px;
  background: rgba(165, 124, 80, 0.1);
  color: #5a4532;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-home:hover {
  background: linear-gradient(135deg, #b68b57, #9a7444);
  color: #fffefb;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(182, 139, 87, 0.3);
}

/* Responsive */
@media (max-width: 600px) {
  .container {
    padding: 28px 20px;
  }
  img {
    width: 170px;
  }
  h2 {
    font-size: 1.6rem;
  }
}


  </style>
</head>
<body>
  <div class="container">
    <h2>Manage UPI QR Code</h2>
    <?php if($msg): ?><div class="msg"><?= $msg ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label><strong>UPI ID:</strong></label>
      <input type="text" name="upi_id" value="<?= htmlspecialchars($qr['upi_id'] ?? '') ?>" required>

      <label><strong>Upload New QR Code (optional):</strong></label>
      <input type="file" name="qr_image" accept="image/*">

      <?php if(!empty($qr['qr_image'])): ?>
        <p><strong>Current QR:</strong></p>
        <img src="<?= htmlspecialchars($qr['qr_image']) ?>" alt="Current QR">
      <?php endif; ?>

      <button type="submit">💾 Save Changes</button>
    </form>

    <a href="index.php" class="btn-home">🏠 Back to Admin Dashboard</a>
  </div>
</body>
</html>
