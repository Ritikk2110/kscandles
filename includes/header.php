<?php
// includes/header.php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= SITE_NAME ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <style>
    /* ============ CART PAGE STYLING ============ */
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #f8f9fc, #e8ebf9);
      color: #333;
      margin: 0;
      padding: 0;
    }

    /* Page Title */
    h1 {
      text-align: center;
      margin: 40px 0 25px;
      font-size: 2.2rem;
      background: linear-gradient(135deg, #5f72bd, #9b23ea);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: 1px;
    }

    /* Empty Cart Message */
    p {
      text-align: center;
      font-size: 1.1rem;
      color: #666;
      margin-top: 40px;
    }

    /* ====== Cart Table ====== */
    .cart-table {
      width: 90%;
      max-width: 1000px;
      margin: 0 auto 40px;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .cart-table thead {
      background: linear-gradient(135deg, #5f72bd, #9b23ea);
      color: #fff;
    }

    .cart-table th,
    .cart-table td {
      padding: 14px 18px;
      text-align: center;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .cart-table th {
      font-weight: 600;
      font-size: 1rem;
      letter-spacing: 0.5px;
    }

    .cart-table tr:hover {
      background: rgba(155, 35, 234, 0.06);
    }

    /* Product Name Column */
    .cart-table td:first-child {
      text-align: left;
      font-weight: 500;
      color: #333;
    }

    /* Buttons */
    .btn {
      display: inline-block;
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: #fff;
      padding: 8px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
    }

    .btn:hover {
      background: linear-gradient(135deg, #667eea, #764ba2);
      transform: translateY(-2px);
    }

    /* ===== Grand Total Section ===== */
    h3 {
      text-align: center;
      font-size: 1.5rem;
      color: #444;
      margin-top: 20px;
    }

    h3 span {
      color: #5f72bd;
    }

    a.btn[href*="checkout"] {
      display: block;
      width: fit-content;
      margin: 25px auto;
      padding: 12px 25px;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(95, 114, 189, 0.3);
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 768px) {
      .cart-table {
        width: 95%;
        font-size: 0.9rem;
      }

      .cart-table thead {
        display: none;
      }

      .cart-table tr {
        display: block;
        margin-bottom: 15px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        padding: 12px;
      }

      .cart-table td {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border: none;
      }

      .cart-table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #555;
      }

      h3 {
        font-size: 1.3rem;
      }
    }
  </style>
</head>
<body>
<header class="site-header">
  <div class="container">
    <a class="brand" href="/kscandles/index.php"><?= SITE_NAME ?></a>
    <nav class="site-nav">
      <a href="/kscandles/index.php">Home</a>
      <a href="/kscandles/shop.php">Shop</a>
      <a href="/kscandles/about.php">About</a>
      <a href="/kscandles/contact.php">Contact</a>
      <a href="/kscandles/cart.php">Cart (<?= array_sum($_SESSION['cart'] ?? []) ?: 0 ?>)</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="/kscandles/user/profile.php">Profile</a>
        <a href="/kscandles/logout.php">Logout</a>
      <?php else: ?>
        <a href="/kscandles/login.php">Login</a>
      <?php endif; ?>
      <?php if(isset($_SESSION['admin_id'])): ?>
        <a href="/kscandles/admin/index.php">Admin</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
