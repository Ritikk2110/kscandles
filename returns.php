<?php 
require_once __DIR__ . '/includes/navbar.php'; 
?>

<style>

/* ==========================
   KS CANDLES - RETURNS POLICY PAGE
   ========================== */

.returns-policy {
  background-color: #f9f6f1;
  color: #3a2e2e;
  font-family: 'Poppins', sans-serif;
  padding: 80px 20px;
  line-height: 1.4;
}

.returns-policy .container {
  max-width: 900px;
  margin: 0 auto;
  background: #fffaf5;
  border-radius: 16px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
  padding: 50px 40px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.returns-policy .container:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.returns-policy h1 {
  text-align: center;
  font-size: 2.2rem;
  color: #2d2424;
  margin-bottom: 25px;
  font-family: 'Cormorant Garamond', serif;
  letter-spacing: 1px;
}

.returns-policy h2 {
  font-size: 1.3rem;
  color: #2d2424;
  border-left: 5px solid #c4a484;
  padding-left: 10px;
  margin-top: 35px;
  margin-bottom: 10px;
  font-weight: 600;
}

.returns-policy p {
  font-size: 1rem;
  margin-bottom: 15px;
  color: #3a2e2e;
}

.returns-policy ul {
  margin-left: 20px;
  padding-left: 20px;
  list-style-type: disc;
}

.returns-policy ul li {
  margin: 8px 0;
  font-size: 0.95rem;
}

.returns-policy a {
  color: #b18142;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease, text-decoration 0.3s ease;
}

.returns-policy a:hover {
  color: #d9a75d;
  text-decoration: underline;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .returns-policy .container {
    padding: 30px 20px;
  }

  .returns-policy h1 {
    font-size: 1.8rem;
  }

  .returns-policy h2 {
    font-size: 1.2rem;
  }
}

</style>

<section class="returns-policy">
  <div class="container">
    <h1>Return & Refund Policy</h1>
    <p>At <strong>KS Candles</strong>, we want you to love every product you purchase. If you’re not completely satisfied, we’re here to help.</p>

    <h2>Eligibility for Returns</h2>
    <ul>
      <li>Items must be returned within <strong>7 days</strong> of delivery.</li>
      <li>Products must be unused, unopened, and in their original packaging.</li>
      <li>Proof of purchase (invoice or receipt) is required for all returns.</li>
    </ul>

    <h2>Non-Returnable Items</h2>
    <ul>
      <li>Used candles or those showing signs of damage not caused during delivery.</li>
      <li>Customized or personalized orders.</li>
    </ul>

    <h2>Refund Process</h2>
    <p>Once your return is received and inspected, we’ll send you an email to notify you that we have received your item. Approved refunds will be processed within <strong>5–7 business days</strong> to your original payment method.</p>

    <h2>Exchange Policy</h2>
    <p>We only replace items if they are defective or damaged during shipping. Please contact us at 
       <a href="mailto:support@kscandles.in">support@kscandles.in</a> with photos of the issue.</p>

    <h2>Need Help?</h2>
    <p>Contact our support team for any return-related queries: 
       <a href="mailto:support@kscandles.in">support@kscandles.in</a></p>
  </div>
</section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
