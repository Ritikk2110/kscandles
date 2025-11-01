<?php
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) redirect('/kscandles/login.php');
if (empty($_SESSION['cart'])) { 
  echo "<p>Your cart is empty.</p>"; 
  require __DIR__.'/includes/footer.php'; 
  exit; 
}

// ✅ Remove abandoned cart if user completes checkout
$uid = $_SESSION['user_id'];
$conn->query("DELETE FROM abandoned_carts WHERE user_id = $uid");

// ✅ Fetch last order for auto-fill
$last_order = $conn->query("SELECT name, address, city, state, pincode FROM orders WHERE user_id = $uid ORDER BY id DESC LIMIT 1")->fetch_assoc();
$prefill = ['name'=>'', 'address'=>'', 'pincode'=>'', 'city'=>'', 'state'=>''];
if ($last_order) $prefill = $last_order;

// ✅ Calculate cart total + shipping
function getTotalWithShipping($conn) {
    $cartTotal = cartTotal($conn);
    $shipping = ($cartTotal >= 799) ? 0 : 149;
    return [$cartTotal, $shipping, $cartTotal + $shipping];
}

list($cartTotal, $shipping, $finalTotal) = getTotalWithShipping($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = $_SESSION['user_email'];
    $address = trim($_POST['address']);
    $pincode = trim($_POST['pincode']);
    $city = trim($_POST['city']) ?: trim($_POST['manualCity']);
    $state = trim($_POST['state']);
    $country = "India";
    $payment_method = 'Manual';

    $conn->begin_transaction();
    try {
        $status = 'Pending';
        $payment_status = 'Pending';

        $stmt = $conn->prepare("INSERT INTO orders 
            (user_id, name, email, address, city, state, pincode, total, created_at, status, payment_method, payment_status) 
            VALUES (?,?,?,?,?,?,?,?,NOW(),?,?,?)");
        $stmt->bind_param("isssssddsss", $_SESSION['user_id'], $name, $email, $address, $city, $state, $pincode, $finalTotal, $status, $payment_method, $payment_status);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        foreach ($_SESSION['cart'] as $pid => $qty) {
            $pstmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = ? FOR UPDATE");
            $pstmt->bind_param("i", $pid);
            $pstmt->execute();
            $prow = $pstmt->get_result()->fetch_assoc();
            $pstmt->close();

            if (!$prow) throw new Exception("Product missing.");
            if ($prow['stock'] < $qty) throw new Exception("Not enough stock for " . $prow['name']);

            $iname = $prow['name'];
            $iprice = $prow['price'];
            $conn->query("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                          VALUES ($order_id, $pid, '".$conn->real_escape_string($iname)."', $iprice, $qty)");

            $upd = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $upd->bind_param("ii", $qty, $pid);
            $upd->execute();
            $upd->close();
        }

        $conn->commit();
        $_SESSION['cart'] = [];
        redirect("payment.php?order_id=$order_id&amount=$finalTotal");

    } catch (Exception $e) {
        $conn->rollback();
        echo "<p>Error placing order: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>



<style>
:root {
  --primary: #8b6d5c;
  --accent: #c4b5a0;
  --bg-light: #faf8f6;
  --text-dark: #2d2424;
  --gradient: linear-gradient(135deg, #C4B5A0, #8B7355);
  --radius: 12px;
  --shadow: 0 8px 25px rgba(0,0,0,0.08);
}
body {font-family:'Poppins',sans-serif;background:var(--bg-light);margin:0;padding:0;color:var(--text-dark);}
h1 {text-align:center;margin:50px 0 30px;font-size:2.3rem;background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
form {max-width:650px;margin:0 auto 60px;background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:45px 50px;display:flex;flex-direction:column;gap:22px;}
form label {display:flex;flex-direction:column;font-weight:600;color:var(--text-dark);}
form input,form textarea,form select {margin-top:6px;padding:12px 14px;border-radius:8px;border:1px solid #ccc;font-size:1rem;outline:none;}
form input:focus,form textarea:focus,form select:focus {border-color:var(--accent);box-shadow:0 0 0 3px rgba(196,181,160,0.25);}
textarea {resize:vertical;min-height:90px;}
.row {display:flex;gap:20px;}
.row label {flex:1;}
.btn {background:var(--gradient);color:#fff;border:none;border-radius:var(--radius);padding:14px;font-weight:600;cursor:pointer;}
.btn:hover {background:linear-gradient(135deg,#8b6d5c,#c4b5a0);}
#manualCity {border:1px solid #ccc;border-radius:8px;padding:10px 12px;width:100%;}
.order-summary {background:#fff;border:1px solid #eee;padding:18px;border-radius:var(--radius);text-align:center;max-width:500px;margin:0 auto 40px;box-shadow:var(--shadow);}
.order-summary p {margin:10px 0;font-size:1rem;}
@media(max-width:600px){form{padding:25px}.row{flex-direction:column;gap:12px}h1{font-size:1.8rem}}
</style>


<h1>Checkout</h1>

<form method="post">
  <label>Full Name
    <input type="text" name="name" value="<?= htmlspecialchars($prefill['name']) ?>" required>
  </label>

  <label>Email *
    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" readonly>
  </label>

  <label>Address
    <textarea name="address" required><?= htmlspecialchars($prefill['address']) ?></textarea>
  </label>

  <div class="row">
    <label>Pincode
      <input type="text" name="pincode" value="<?= htmlspecialchars($prefill['pincode']) ?>" required pattern="\d{6}" maxlength="6">
    </label>
    <label>State
      <select name="state" id="state" required>
        <option value="">Select State</option>
      </select>
    </label>
  </div>

  <div class="row">
    <label>City
      <select name="city" id="city" style="margin-bottom:8px;">
        <option value="">Select City</option>
      </select>
      <input type="text" id="manualCity" name="manualCity" placeholder="Or type your city manually">
    </label>
    <label>Country
      <input type="text" name="country" value="India" readonly>
    </label>
  </div>

  <div class="order-summary">
    <p><strong>Subtotal:</strong> ₹<?= number_format($cartTotal, 2) ?></p>
    <p><strong>Shipping:</strong> <?= $shipping > 0 ? "₹$shipping" : "<span style='color:green;'>Free</span>" ?></p>
    <p><strong>Final Total:</strong> <span style="font-size:1.3em;color:#8B7355;">₹<?= number_format($finalTotal, 2) ?></span></p>
  </div>

  <button class="btn" type="submit">Proceed to Payment</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>

<script>
// ✅ All Indian States and Major Cities
const stateCityMap = {
  "Andhra Pradesh": ["Visakhapatnam","Vijayawada","Guntur","Nellore","Kurnool"],
  "Arunachal Pradesh": ["Itanagar","Tawang","Ziro"],
  "Assam": ["Guwahati","Dibrugarh","Silchar"],
  "Bihar": ["Patna","Gaya","Bhagalpur"],
  "Chhattisgarh": ["Raipur","Bilaspur","Korba"],
  "Goa": ["Panaji","Margao"],
  "Gujarat": ["Ahmedabad","Surat","Vadodara","Rajkot"],
  "Haryana": ["Gurugram","Faridabad","Panipat"],
  "Himachal Pradesh": ["Shimla","Manali","Dharamshala"],
  "Jharkhand": ["Ranchi","Jamshedpur","Dhanbad"],
  "Karnataka": ["Bengaluru","Mysuru","Hubli","Mangalore"],
  "Kerala": ["Kochi","Thiruvananthapuram","Kozhikode"],
  "Madhya Pradesh": ["Bhopal","Indore","Gwalior","Jabalpur"],
  "Maharashtra": ["Mumbai","Pune","Nagpur","Nashik","Aurangabad"],
  "Manipur": ["Imphal"],
  "Meghalaya": ["Shillong"],
  "Mizoram": ["Aizawl"],
  "Nagaland": ["Kohima","Dimapur"],
  "Odisha": ["Bhubaneswar","Cuttack","Rourkela"],
  "Punjab": ["Ludhiana","Amritsar","Jalandhar"],
  "Rajasthan": ["Jaipur","Jodhpur","Udaipur","Kota"],
  "Sikkim": ["Gangtok"],
  "Tamil Nadu": ["Chennai","Coimbatore","Madurai","Tiruchirappalli"],
  "Telangana": ["Hyderabad","Warangal","Nizamabad"],
  "Tripura": ["Agartala"],
  "Uttar Pradesh": ["Lucknow","Kanpur","Varanasi","Noida","Agra","Ghaziabad"],
  "Uttarakhand": ["Dehradun","Haridwar","Nainital"],
  "West Bengal": ["Kolkata","Howrah","Siliguri","Durgapur"],
  "Delhi": ["New Delhi"]
};

const stateSelect = document.getElementById('state');
const citySelect = document.getElementById('city');
const preState = "<?= htmlspecialchars($prefill['state']) ?>";
const preCity = "<?= htmlspecialchars($prefill['city']) ?>";

for (const state in stateCityMap) {
  const opt = document.createElement('option');
  opt.value = state;
  opt.textContent = state;
  if (state === preState) opt.selected = true;
  stateSelect.appendChild(opt);
}

function updateCities() {
  const cities = stateCityMap[stateSelect.value] || [];
  citySelect.innerHTML = '<option value="">Select City</option>';
  cities.forEach(city => {
    const opt = document.createElement('option');
    opt.value = city;
    opt.textContent = city;
    if (city === preCity) opt.selected = true;
    citySelect.appendChild(opt);
  });
}
stateSelect.addEventListener('change', updateCities);
if (preState) updateCities();
</script>

