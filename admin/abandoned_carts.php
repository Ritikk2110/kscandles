<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

// Fetch abandoned carts
$sql = "
  SELECT a.*, u.name, u.email 
  FROM abandoned_carts a
  JOIN users u ON a.user_id = u.id
  ORDER BY a.created_at DESC
";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Abandoned Carts</title>
  <style>
    
  /* === KSCandles Admin - Abandoned Carts === */
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #FFF8F0, #F4E1C6);
    color: #4B3D2A;
    margin: 0;
    padding: 40px 20px;
  }

  .container {
    max-width: 1100px;
    margin: 0 auto;
    background: #FFFFFF;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(139, 115, 85, 0.15);
    padding: 35px;
    animation: fadeIn 0.6s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }

  h2 {
    text-align: center;
    font-size: 1.8rem;
    background: linear-gradient(135deg, #C4B5A0, #8B7355);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    margin-top: 0;
    margin-bottom: 25px;
    letter-spacing: 1px;
  }

  .back-btn {
    display: inline-block;
    background: linear-gradient(135deg, #C4B5A0, #8B7355);
    color: #fff;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  }

  .back-btn:hover {
    background: linear-gradient(135deg, #BFA88B, #9A7C5A);
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.15);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 12px;
    overflow: hidden;
  }

  th {
    background: linear-gradient(135deg, #F6EDE3, #E9D6BE);
    color: #5A4731;
    padding: 14px;
    font-size: 1rem;
    text-align: left;
  }

  td {
    padding: 14px 16px;
    font-size: 0.95rem;
    color: #3E2E1F;
    border-bottom: 1px solid #F1E6D8;
    vertical-align: top;
  }

  tr:nth-child(even) {
    background-color: #FFF9F3;
  }

  tr:hover {
    background: #FFF3E6;
    transition: 0.25s ease;
  }

  .cart-details {
    font-size: 0.9rem;
    color: #5C4C38;
    background: #FFF6E9;
    border-radius: 10px;
    padding: 8px 12px;
    margin-top: 6px;
    box-shadow: inset 0 0 6px rgba(196, 181, 160, 0.2);
  }

  p {
    text-align: center;
    color: #7B6651;
    font-size: 1rem;
    padding: 10px 0;
  }

  @media (max-width: 768px) {
    .container {
      padding: 20px;
    }
    table, th, td {
      font-size: 0.9rem;
    }
    .back-btn {
      padding: 8px 16px;
      font-size: 0.9rem;
    }
  }


  </style>
</head>
<body>
  <div class="container">
    <a href="index.php" class="back-btn">← Back to Dashboard</a>
    <h2>🛒 Abandoned Carts</h2>
    <?php if ($res->num_rows > 0): ?>
    <table>
      <tr>
        <th>User</th>
        <th>Email</th>
        <th>Items</th>
        <th>Date</th>
      </tr>
      <?php while ($row = $res->fetch_assoc()): 
        $cart = json_decode($row['cart_data'], true);
      ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
          <?php if (!empty($cart)): ?>
            <?php foreach ($cart as $pid => $qty): ?>
              <div class="cart-details">Product ID #<?= $pid ?> — Qty: <?= $qty ?></div>
            <?php endforeach; ?>
          <?php else: ?>
            <em>No items recorded</em>
          <?php endif; ?>
        </td>
        <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
    <?php else: ?>
      <p style="text-align:center;">✅ No abandoned carts found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
