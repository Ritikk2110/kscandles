<?php
// includes/footer.php
?>
<style>
/* ============================================= */
/* FOOTER STYLES */
/* ============================================= */
.footer {
  background: linear-gradient(135deg, #2D2424, #3B2F2F);
  color: #EDE7E1;
  padding: 60px 0 25px;
  
  font-family: 'Inter', sans-serif;
}

.footer-container {
  width: 90%;
  max-width: 1200px;
  margin: 0 auto;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 50px;
  margin-bottom: 50px;
}

.footer-logo {
  
   font-family: 'Inter', sans-serif;
 /* font-family: 'Cormorant Garamond', serif;*/
  font-size: 2rem;
  font-weight: 600;
  color: #F1E1C6;
  margin-bottom: 15px;
}

.footer-desc {
  color: #C4B5A0;
  font-size: 0.95rem;
  line-height: 1.4;
  margin-bottom: 18px;
}

.footer-section h4 {
  font-family: 'Inter', sans-serif;
 
  /*font-family: 'Cormorant Garamond', serif;*/
  font-size: 1.2rem;
  color: #F1E1C6;
  margin-bottom: 15px;
  font-weight: 600;
  border-bottom: 2px solid #8B7355;
  display: inline-block;
  padding-bottom: 4px;
}

.footer-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-section ul li {
  margin-bottom: 8px;
}

.footer-section a {
  color: #EDE7E1;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.footer-section a:hover {
  color: #D6C4A6;
  text-decoration: underline;
}

.social-links {
  display: flex;
  align-items: center;
  margin-top: 10px;
  gap: 16px;
}

.social-links a {
  color: #C4B5A0;
  font-size: 1.4rem;
  transition: all 0.3s ease;
}

.social-links a:hover {
  color: #fff;
  transform: scale(1.15);
}

.footer-bottom {
  border-top: 1px solid #C4B5A0;
  text-align: center;
  padding-top: 15px;
  font-size: 0.9rem;
  color: #C4B5A0;
}

/* Responsive */
@media (max-width: 768px) {
  .footer-content {
    grid-template-columns: 1fr 1fr;
    gap: 30px;
  }

  .footer-section {
    text-align: left;
  }
}

@media (max-width: 480px) {
  .footer-content {
    grid-template-columns: 1fr;
  }

  .footer-section {
    text-align: center;
  }

  .social-links {
    justify-content: center;
  }
}
</style>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-content">

      <!-- Brand Section -->
      <div class="footer-section">
        <h3 class="footer-logo">RScandles</h3>
        <p class="footer-desc">Premium handcrafted candles for your perfect moments.</p>
        <div class="social-links">
          <a href="https://www.instagram.com/kscandles" target="_blank" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://twitter.com/kscandles" target="_blank" aria-label="Twitter">
            <i class="fab fa-x-twitter"></i>
          </a>
          <a href="https://www.facebook.com/kscandles" target="_blank" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="https://www.youtube.com/@kscandles" target="_blank" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
          <a href="https://www.pinterest.com/kscandles" target="_blank" aria-label="Pinterest">
            <i class="fab fa-pinterest-p"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-section">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="/kscandles/index.php">Home</a></li>
          <li><a href="/kscandles/products.php">Products</a></li>
          <li><a href="/kscandles/contact.php">Contact</a></li>
          <li><a href="/kscandles/login.php">Account</a></li>
          <li><a href="/kscandles/admin/login.php">Admin</a></li>
        </ul>
      </div>

      <!-- Customer Service -->
      <div class="footer-section">
        <h4>Customer Service</h4>
        <ul>
          <li><a href="/kscandles/contact.php">Contact Us</a></li>
          <li><a href="/kscandles/shipping.php">Shipping Info</a></li>
          <li><a href="/kscandles/returns.php">Returns</a></li>
          <li><a href="/kscandles/faq.php">FAQ</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="footer-section">
        <h4>Contact Info</h4>
        <p>📧 <a href="mailto:inforscandles@gmail.com">info@candles.in</a></p>
        <p>📞 +91 98765 43210</p>
        <p>📍 123 Candle Street, Lucknow, India</p>
      </div>

    </div>

    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <strong>KSCandles.</strong>  All rights reserved.</p>
      <p>Designed by <strong>Creavate Technologies</strong> </p>
    </div>
  </div>
</footer>

<!-- ✅ Correct Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="/kscandles/assets/js/script.js"></script>



</body>
</html>
