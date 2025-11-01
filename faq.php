<?php 
require_once __DIR__ . '/includes/navbar.php'; 
?>

<style>


  /* ==========================
   KS CANDLES - FAQ PAGE
   ========================== */

.faq-section {
  background-color: #f9f6f1;
  padding: 80px 20px;
  font-family: 'Poppins', sans-serif;
  color: #3a2e2e;
  line-height: 1.4;
}

.faq-section .container {
  max-width: 900px;
  margin: 0 auto;
  background: #fffaf5;
  border-radius: 16px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
  padding: 50px 40px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.faq-section .container:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

/* ====== Heading ====== */
.faq-section h1 {
  text-align: center;
  font-size: 2.2rem;
  color: #2d2424;
  margin-bottom: 40px;
  font-family: 'Cormorant Garamond', serif;
  letter-spacing: 1px;
}

/* ====== FAQ Items ====== */
.faq-item {
  background: #fff;
  border-left: 5px solid #c4a484;
  border-radius: 10px;
  margin-bottom: 25px;
  padding: 18px 22px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.faq-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.faq-item h2 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #2d2424;
  display: flex;
  align-items: center;
  gap: 6px;
}

.faq-item h2::before {
  content: "❓";
  color: #c4a484;
  font-size: 1.2rem;
}

.faq-item p {
  margin-top: 10px;
  color: #4a3d3d;
  font-size: 0.95rem;
  line-height: 1.4;
}

/* ====== Responsive ====== */
@media (max-width: 768px) {
  .faq-section .container {
    padding: 30px 20px;
  }

  .faq-section h1 {
    font-size: 1.8rem;
  }

  .faq-item h2 {
    font-size: 1.05rem;
  }
}

</style>

<section class="faq-section">
  <div class="container">
    <h1>Frequently Asked Questions</h1>

    <div class="faq-item">
      <h2>1. How long does delivery take?</h2>
      <p>Orders are usually delivered within <strong>3–5 business days</strong> depending on your location.</p>
    </div>

    <div class="faq-item">
      <h2>2. What payment methods do you accept?</h2>
      <p>We accept all major credit/debit cards, UPI, and net banking via secure payment gateways.</p>
    </div>

    <div class="faq-item">
      <h2>3. Can I cancel my order?</h2>
      <p>Orders can be canceled within <strong>12 hours</strong> of placement. Please contact our support team immediately.</p>
    </div>

    <div class="faq-item">
      <h2>4. Do you offer bulk or custom orders?</h2>
      <p>Yes! We provide bulk discounts and custom labeling for corporate or event orders. Contact us for more info.</p>
    </div>

    <div class="faq-item">
      <h2>5. What if my product arrives damaged?</h2>
      <p>Please email us a photo of the damaged item within <strong>48 hours</strong> of delivery. We’ll send a replacement or refund promptly.</p>
    </div>
  </div>
</section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
