<?php
// admin/edit_product.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$uploadDir = __DIR__ . '/../uploads/products/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: products.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) {
    header('Location: products.php');
    exit;
}

$catsRes = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $catsRes->fetch_all(MYSQLI_ASSOC);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price = trim($_POST['price'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = "Product name is required.";
    if ($price === '' || !is_numeric($price)) $errors[] = "Valid price is required.";
    if ($category_id <= 0) $errors[] = "Please select a category.";

    $newImageName = null;
    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image'];
        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
        if ($img['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Image upload error.";
        } elseif (!in_array($img['type'], $allowed)) {
            $errors[] = "Only JPG/PNG/WEBP/GIF images allowed.";
        } else {
            $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
            $newImageName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = $uploadDir . $newImageName;
            if (!move_uploaded_file($img['tmp_name'], $dest)) {
                $errors[] = "Failed to move uploaded image.";
                $newImageName = null;
            }
        }
    }

    if (empty($errors)) {
        if ($newImageName) {
            $oldImage = $product['image'];
            $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, description=?, image=? WHERE id=?");
            $stmt->bind_param("siddssi", $name, $category_id, $price, $stock, $description, $newImageName, $id);
        } else {
            $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, description=? WHERE id=?");
            $stmt->bind_param("siddsi", $name, $category_id, $price, $stock, $description, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();
            if (!empty($newImageName) && !empty($oldImage)) {
                $oldPath = $uploadDir . $oldImage;
                if (is_file($oldPath)) @unlink($oldPath);
            }
            header('Location: products.php?msg=updated');
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Product - Admin Panel</title>

  <style>
    

    /* ============================
   KSCandles Admin Theme - Edit Product
   Creamy White (Warm Candlelight Theme)
   ============================ */

:root {
  --bg-light: #f8f6f3;
  --cream: #f5ede0;
  --primary-gradient: linear-gradient(135deg, #C4B5A0, #8B7355);
  --primary: #8B7355;
  --text-dark: #3b2f2f;
  --text-light: #7c6a58;
  --white: #ffffff;
  --shadow: rgba(0, 0, 0, 0.08);
  --radius: 12px;
  --transition: all 0.3s ease;
  --font-main: 'Poppins', 'Segoe UI', sans-serif;
}

/* ----------- General ----------- */
body {
  background-color: var(--bg-light);
  font-family: var(--font-main);
  color: var(--text-dark);
  margin: 0;
  padding: 0;
}

/* ----------- Container ----------- */
.admin-wrap {
  max-width: 720px;
  margin: 70px auto;
  background: var(--white);
  border-radius: var(--radius);
  padding: 40px 45px;
  box-shadow: 0 6px 25px var(--shadow);
  border: 1px solid #eee;
  transition: var(--transition);
}
.admin-wrap:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.1);
}

/* ----------- Headings ----------- */
h2 {
  text-align: center;
  font-size: 26px;
  font-weight: 600;
  background: var(--primary-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 30px;
}

/* ----------- Labels ----------- */
label {
  display: block;
  margin-top: 18px;
  font-weight: 600;
  color: var(--text-light);
  font-size: 15px;
}

/* ----------- Inputs & Textarea ----------- */
input[type="text"],
input[type="number"],
select,
textarea {
  width: 100%;
  padding: 12px 14px;
  margin-top: 8px;
  border-radius: var(--radius);
  border: 1px solid #d8d2c6;
  background: var(--cream);
  font-size: 15px;
  color: var(--text-dark);
  transition: var(--transition);
}

input:focus,
textarea:focus,
select:focus {
  border-color: #8B7355;
  box-shadow: 0 0 6px rgba(139, 115, 85, 0.3);
  outline: none;
  background: #fffaf2;
}

/* ----------- Buttons ----------- */
button,
.btn-secondary {
  display: inline-block;
  border: none;
  border-radius: var(--radius);
  cursor: pointer;
  font-weight: 500;
  letter-spacing: 0.3px;
  padding: 12px 20px;
  font-size: 15px;
  transition: var(--transition);
}

button {
  background: var(--primary-gradient);
  color: var(--white);
  box-shadow: 0 4px 10px rgba(139, 115, 85, 0.3);
}
button:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 15px rgba(139, 115, 85, 0.4);
}

.btn-secondary {
  background: #d1c6b1;
  color: var(--text-dark);
  text-decoration: none;
}
.btn-secondary:hover {
  background: #c3b69e;
}

/* ----------- Image Preview ----------- */
img {
  border-radius: var(--radius);
  border: 1px solid #ddd;
  background: #fffaf3;
  padding: 6px;
  margin-top: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
}

/* ----------- Error Message ----------- */
.error {
  background: #fff1f1;
  color: #a33;
  padding: 12px;
  border-radius: var(--radius);
  margin-bottom: 20px;
  border: 1px solid #f5c2c2;
  font-size: 14px;
  box-shadow: 0 3px 8px rgba(255, 0, 0, 0.08);
}

/* ----------- Responsive ----------- */
@media (max-width: 600px) {
  .admin-wrap {
    margin: 25px;
    padding: 25px;
  }
  h2 {
    font-size: 22px;
  }
}

  </style>
</head>
<body>
  <div class="admin-wrap">
    <h2>Edit Product</h2>
    <?php if (!empty($errors)): ?>
      <div class="error"><?= htmlspecialchars(implode('<br>', $errors)) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label>Product Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

      <label>Category</label>
      <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= ($product['category_id'] == $c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Price (e.g. 399.00)</label>
      <input type="text" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

      <label>Stock</label>
      <input type="number" name="stock" min="0" value="<?= (int)$product['stock'] ?>">

      <label>Description</label>
      <textarea name="description" rows="6"><?= htmlspecialchars($product['description']) ?></textarea>

      <label>Current Image</label><br>
      <?php if (!empty($product['image']) && is_file(__DIR__ . '/../uploads/products/' . $product['image'])): ?>
        <img src="<?= '../uploads/products/' . htmlspecialchars($product['image']) ?>" alt="current" style="max-width:180px;display:block;margin-bottom:12px;">
      <?php else: ?>
        <div style="margin-bottom:12px;color:#666;">No image uploaded</div>
      <?php endif; ?>

      <label>Replace Image (optional)</label>
      <input type="file" name="image" accept="image/*">

      <br><br>
      <div style="display:flex;align-items:center;gap:10px;">
        <button type="submit">Update Product</button>
        <a href="products.php" class="btn-secondary">Back to Products</a>
      </div>
    </form>
  </div>
</body>
</html>
