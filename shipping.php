<?php 
require_once __DIR__ . '/includes/navbar.php'; 
?>

<style>


  /* ==========================
   KS CANDLES - SHIPPING POLICY PAGE
   ========================== */

.shipping-policy {
  background-color: #f9f6f1;
  font-family: 'Poppins', sans-serif;
  color: #3a2e2e;
  padding: 80px 20px;
  line-height: 1.4;
}

.shipping-policy .container {
  max-width: 900px;
  margin: 0 auto;
  background: #fffaf5;
  border-radius: 16px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
  padding: 50px 40px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.shipping-policy .container:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.shipping-policy h1 {
  text-align: center;
  font-size: 2.2rem;
  color: #2d2424;
  margin-bottom: 25px;
  font-family: 'Cormorant Garamond', serif;
  letter-spacing: 1px;
}

.shipping-policy h2 {
  font-size: 1.3rem;
  color: #2d2424;
  border-left: 5px solid #c4a484;
  padding-left: 10px;
  margin-top: 35px;
  margin-bottom: 10px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
}

.shipping-policy h2::before {
  content: "•";
  color: #c4a484;
  font-size: 1.4rem;
  line-height: 1;
}

.shipping-policy p {
  font-size: 1rem;
  margin-bottom: 15px;
  color: #3a2e2e;
}

.shipping-policy ul {
  list-style-type: disc;
  margin-left: 20px;
  padding-left: 20px;
}

.shipping-policy ul li {
  margin: 8px 0;
  font-size: 0.95rem;
}

.shipping-policy a {
  color: #b18142;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease, text-decoration 0.3s ease;
}

.shipping-policy a:hover {
  color: #d9a75d;
  text-decoration: underline;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .shipping-policy .container {
    padding: 30px 20px;
  }

  .shipping-policy h1 {
    font-size: 1.8rem;
  }

  .shipping-policy h2 {
    font-size: 1.2rem;
  }
}

</style>

<section class="shipping-policy">
  <div class="container">
    <h1>Shipping & Delivery Policy</h1>

    <p>At <strong>KS Candles</strong>, we aim to ensure your order reaches you quickly and safely. We partner with reliable courier services to deliver candles with care and on time.</p>

    <h2>📦 Order Processing Time</h2>
    <ul>
      <li>All orders are processed within <strong>1–2 business days</strong> after confirmation.</li>
      <li>Orders placed on weekends or public holidays will be processed the next business day.</li>
    </ul>

    <h2>🚚 Delivery Time</h2>
    <ul>
      <li>Standard delivery usually takes <strong>3–5 business days</strong>.</li>
      <li>Remote areas may take up to <strong>7–10 business days</strong>.</li>
    </ul>

    <h2>🌍 Delivery Areas</h2>
    <p>We currently ship across <strong>India</strong>. International shipping is not available at this time.</p>

    <h2>💰 Shipping Charges</h2>
    <ul>
      <li>Free shipping on orders above <strong>₹999</strong>.</li>
      <li>A standard charge of <strong>₹60</strong> applies to orders below ₹999.</li>
    </ul>

    <h2>📦 Tracking Your Order</h2>
    <p>Once your order is shipped, you will receive an email and SMS with the tracking ID and a link to monitor your shipment in real-time.</p>

    <h2>❗ Delays & Exceptions</h2>
    <p>In rare cases, delays may occur due to factors beyond our control such as natural calamities, strikes, or courier service issues. We will notify you promptly in such cases.</p>

    <h2>📞 Need Assistance?</h2>
    <p>For any shipping or delivery-related concerns, feel free to reach us at 
      <a href="mailto:support@kscandles.in">support@kscandles.in</a> or call us at <strong>+91-98765 43210</strong>.
    </p>
  </div>
</section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
