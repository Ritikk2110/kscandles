<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) header('Location: login.php');

$msg = '';
$categories = [];

// ✅ Fetch all categories
$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// ✅ Handle new category addition (AJAX)
if (isset($_POST['new_category'])) {
    $newCat = trim($_POST['new_category']);
    if ($newCat !== '') {
        // Check if category exists
        $check = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $check->bind_param("s", $newCat);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            echo json_encode(["status" => "exists", "id" => $row['id'], "name" => $newCat]);
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $newCat);
            $stmt->execute();
            echo json_encode(["status" => "success", "id" => $stmt->insert_id, "name" => $newCat]);
        }
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

// ✅ Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['new_category'])) {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = isset($_POST['category']) ? (int)$_POST['category'] : 0;
    $description = trim($_POST['description']);

    // ✅ Image upload handling
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $imageName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileType, $allowedTypes)) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $msg = "❌ Failed to upload image.";
            }
        } else {
            $msg = "❌ Only JPG, JPEG, PNG, and WEBP files are allowed.";
        }
    }

    if ($name !== '' && $price > 0 && $msg === '') {
        $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image, category_id, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisss", $name, $price, $stock, $imageName, $category_id, $description);
        $stmt->execute();

        $_SESSION['msg'] = "✅ Product added successfully!";
        header("Location: products.php");
        exit;
    } elseif ($msg === '') {
        $msg = "❌ Please fill all required fields.";
    }
}
?>
<!doctype html>
<html>
<head>
  <title>Add Product</title>
  <style>
    /* ============================
   KSCandles Admin - Add Product
   Theme: Creamy White (Warm Candlelight)
   ============================ */

:root {
  --bg-light: #f8f6f3;
  --cream: #f5ede0;
  --primary-gradient: linear-gradient(135deg, #C4B5A0, #8B7355);
  --primary: #8B7355;
  --secondary: #C4B5A0;
  --text-dark: #3b2f2f;
  --text-light: #7c6a58;
  --white: #ffffff;
  --shadow: rgba(0, 0, 0, 0.08);
  --radius: 12px;
  --transition: all 0.3s ease;
  --font-main: 'Poppins', 'Segoe UI', sans-serif;
}

/* ----------- Global ----------- */
body {
  font-family: var(--font-main);
  background-color: var(--bg-light);
  color: var(--text-dark);
  margin: 0;
  padding: 0;
}

/* ----------- Header ----------- */
header {
  background: var(--primary-gradient);
  color: var(--white);
  padding: 16px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}
header h2 {
  margin: 0;
  font-weight: 600;
  font-size: 22px;
}
header a {
  color: var(--white);
  text-decoration: none;
  background: rgba(255, 255, 255, 0.2);
  padding: 8px 16px;
  border-radius: var(--radius);
  margin-left: 10px;
  font-size: 14px;
  transition: var(--transition);
}
header a:hover {
  background: rgba(255, 255, 255, 0.35);
}

/* ----------- Form Container ----------- */
main {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 60px 0;
}

.form-container {
  background: var(--white);
  padding: 40px 45px;
  border-radius: var(--radius);
  box-shadow: 0 8px 25px var(--shadow);
  width: 520px;
  border: 1px solid #eee;
  transition: var(--transition);
}
.form-container:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
  text-align: center;
  margin-bottom: 25px;
  font-weight: 600;
  font-size: 24px;
  background: var(--primary-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ----------- Labels & Inputs ----------- */
label {
  display: block;
  margin-bottom: 6px;
  color: var(--text-light);
  font-weight: 500;
  font-size: 14.5px;
}

input[type="text"],
input[type="number"],
input[type="file"],
select,
textarea {
  width: 100%;
  padding: 12px 14px;
  margin-bottom: 20px;
  border: 1px solid #d9d0c4;
  border-radius: var(--radius);
  background: var(--cream);
  color: var(--text-dark);
  font-size: 15px;
  transition: var(--transition);
}

input:focus,
textarea:focus,
select:focus {
  border-color: var(--primary);
  background: #fffaf3;
  box-shadow: 0 0 6px rgba(139, 115, 85, 0.3);
  outline: none;
}

/* ----------- Buttons ----------- */
button {
  display: inline-block;
  width: 100%;
  background: var(--primary-gradient);
  color: var(--white);
  padding: 12px 18px;
  font-size: 15px;
  border: none;
  border-radius: var(--radius);
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
}
button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(139, 115, 85, 0.4);
}

/* ----------- Category Row ----------- */
.category-row {
  display: flex;
  align-items: center;
  gap: 10px;
}
.add-btn {
  background: #b79c78;
  color: var(--white);
  padding: 8px 12px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-size: 13.5px;
  transition: var(--transition);
}
.add-btn:hover {
  background: #a1825d;
}

/* ----------- Messages ----------- */
.msg {
  text-align: center;
  margin-bottom: 15px;
  padding: 10px;
  border-radius: var(--radius);
  font-size: 14px;
  background: #fff6f6;
  color: #a33;
  border: 1px solid #f5c2c2;
}
.success {
  background: #f0fff4;
  color: #317a39;
  border: 1px solid #a0d8a0;
}

/* ----------- Modal ----------- */
#addCategoryModal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(59, 47, 47, 0.4);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content {
  background: var(--white);
  padding: 25px 20px;
  border-radius: var(--radius);
  width: 300px;
  text-align: center;
  box-shadow: 0 6px 25px var(--shadow);
  border-top: 5px solid #b79c78;
}
.modal-content h3 {
  margin-bottom: 15px;
  color: var(--primary);
  font-size: 18px;
}
.modal-content input {
  width: 90%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #d6cab5;
  background: var(--cream);
  font-size: 14px;
  margin-bottom: 15px;
}
.modal-content input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 6px rgba(139, 115, 85, 0.3);
}
.modal-content button {
  width: auto;
  margin: 0 6px;
  padding: 8px 15px;
  background: var(--primary-gradient);
  font-size: 14px;
}
.modal-content button:hover {
  transform: translateY(-1px);
}

/* ----------- Responsive ----------- */
@media (max-width: 600px) {
  .form-container {
    width: 90%;
    padding: 25px;
  }
  header {
    padding: 12px 20px;
  }
  header h2 {
    font-size: 18px;
  }
}

  </style>
</head>
<body>

<header>
  <h2>Admin Panel</h2>
  <div>
    <a href="products.php">Back to Products</a>
    <a href="index.php">Dashboard</a>
  </div>
</header>

<main>
  <div class="form-container">
    <h2>Add Product</h2>
    <?php if($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" id="productForm">
      <label>Product Name *</label>
      <input type="text" name="name" placeholder="Enter product name" required>

      <label>Price (₹) *</label>
      <input type="number" name="price" step="0.01" placeholder="Enter price" required>

      <label>Stock Quantity</label>
      <input type="number" name="stock" value="0" min="0">

      <label>Category *</label>
      <div class="category-row">
        <select name="category" id="categorySelect" required>
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="button" class="add-btn" onclick="openCategoryModal()">+ Add</button>
      </div>

      <label>Description *</label>
      <textarea name="description" rows="4" placeholder="Enter product description" required></textarea>

      <label>Product Image *</label>
      <input type="file" name="image" accept="image/*" required>

      <button type="submit">Add Product</button>
    </form>
  </div>
</main>

<!-- ✅ Add Category Modal -->
<div id="addCategoryModal">
  <div class="modal-content">
    <h3>Add New Category</h3>
    <input type="text" id="newCategoryName" placeholder="Enter category name">
    <br>
    <button onclick="saveCategory()">Save</button>
    <button onclick="closeCategoryModal()">Cancel</button>
  </div>
</div>

<script>
function openCategoryModal() {
  document.getElementById('addCategoryModal').style.display = 'flex';
}
function closeCategoryModal() {
  document.getElementById('addCategoryModal').style.display = 'none';
}
function saveCategory() {
  const catName = document.getElementById('newCategoryName').value.trim();
  if (!catName) return alert('Please enter a category name');

  const formData = new FormData();
  formData.append('new_category', catName);

  fetch('', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success' || data.status === 'exists') {
        const select = document.getElementById('categorySelect');
        const option = document.createElement('option');
        option.value = data.id;
        option.textContent = data.name;
        select.appendChild(option);
        select.value = data.id; // Auto-select new category
        closeCategoryModal();
        alert('✅ Category added successfully!');
      } else {
        alert('❌ Failed to add category.');
      }
    })
    .catch(() => alert('❌ Error adding category.'));
}
</script>

</body>
</html>
