<?php
// about.php
if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . '/includes/navbar.php';
?>
  
  <style>
  /* ===================================================
     KSCANDLES - ABOUT PAGE (Refined Candle Theme)
     =================================================== */
  
  .about-page {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #fffaf3, #f6f2ec);
    color: #2d2424;
    line-height: 1.4;
    overflow-x: hidden;
  }
  
  /* Container */
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }
  
  /* Headings */
  h2 {
    text-align: center;
    font-family: "Cormorant Garamond", serif;
    font-size: 2.2rem;
    color: #8b7355;
    margin-bottom: 30px;
    position: relative;
  }
  h2::after {
    content: "";
    width: 60px;
    height: 3px;
    display: block;
    background: #c4b5a0;
    margin: 10px auto 0;
    border-radius: 5px;
  }
  
  /* ======================
     HERO SECTION
  ====================== */
  .about-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 50px;
    padding: 80px 20px;
    max-width: 1200px;
    margin: auto;
  }
  .about-hero-inner {
    flex: 1;
  }
  .about-hero h1 {
    font-family: "Cormorant Garamond", serif;
    font-size: 3rem;
    color: #2d2424;
  }
  .about-hero .brand {
    background: linear-gradient(135deg, #c4b5a0, #8b7355);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .about-hero .lead {
    font-size: 1.1rem;
    color: #6b5f4a;
    margin: 20px 0 30px;
  }
  .hero-cta {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
  }
  
  /* Buttons */
  .btn {
    display: inline-block;
    padding: 10px 22px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  .btn.primary {
    background: linear-gradient(135deg, #c4b5a0, #8b7355);
    color: #fff;
  }
  .btn.primary:hover {
    background: linear-gradient(135deg, #8b7355, #c4b5a0);
    transform: translateY(-3px);
  }
  .btn.outline {
    border: 1px solid #8b7355;
    color: #8b7355;
    background: transparent;
  }
  .btn.outline:hover {
    background: #8b7355;
    color: #fff;
    transform: translateY(-3px);
  }
  
  /* Hero Candle */
  .about-hero-image {
    width: 300px;
    display: flex;
    justify-content: center;
  }
  .hero-candle {
    width: 200px;
    height: 280px;
    background: linear-gradient(135deg, #c4b5a0, #8b7355);
    border-radius: 40% 40% 30% 30% / 60% 60% 40% 40%;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }
  .hero-candle .flame {
    font-size: 32px;
    margin-top: -22px;
    animation: flicker 1s infinite alternate ease-in-out;
  }
  @keyframes flicker {
    0% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0.8; transform: translateY(-6px); }
  }
  
  /* STORY */
  .about-story {
    background: #fff;
    text-align: center;
    padding: 70px 20px;
    max-width: 900px;
    margin: 0 auto 60px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  }
  .about-story p {
    color: #6b5f4a;
    font-size: 1.05rem;
    margin-bottom: 18px;
  }
  
  /* VALUES */
  .about-values {
    padding: 60px 20px;
  }
  .values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
  }
  .value-card {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: transform 0.3s ease;
  }
  .value-card:hover {
    transform: translateY(-6px);
  }
  .value-card h3 {
    color: #8b7355;
    margin-bottom: 10px;
    font-family: "Cormorant Garamond", serif;
  }
  .value-card p {
    color: #6b5f4a;
    font-size: 0.95rem;
  }
  
  /* TEAM */
  .about-team {
    padding: 60px 20px;
    text-align: center;
  }
  .team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
  }
  .team-member {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    padding: 18px;
    transition: transform 0.3s ease;
  }
  .team-member:hover {
    transform: translateY(-6px);
  }
  .team-member img {
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
  }
  .team-member h4 {
    color: #2d2424;
    margin-bottom: 5px;
  }
  .team-member p {
    color: #6b5f4a;
    font-size: 0.9rem;
  }
  
  /* PROCESS */
  .about-process {
    padding: 60px 20px;
  }
  .process-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
  }
  .process-step {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    display: flex;
    gap: 10px;
    align-items: flex-start;
    transition: transform 0.3s ease;
  }
  .process-step:hover {
    transform: translateY(-5px);
  }
  .process-step span {
    font-size: 1.2rem;
    color: #8b7355;
    font-weight: 700;
  }
  
  /* CALLOUT */
  .about-callout {
    margin: 50px 0;
    background: linear-gradient(135deg, #8b7355, #c4b5a0);
    color: #fff;
    text-align: center;
    padding: 40px 20px;
    border-radius: 15px;
  }
  .callout-inner h3 {
    font-size: 1.3rem;
    margin-bottom: 20px;
    font-family: "Cormorant Garamond", serif;
  }
  .callout-inner .btn.primary {
    background: #fff;
    color: #8b7355;
  }
  .callout-inner .btn.primary:hover {
    background: #f5f1ea;
    transform: translateY(-2px);
  }
  
  /* RESPONSIVE */
  @media (max-width: 900px) {
    .about-hero {
      flex-direction: column;
      text-align: center;
    }
    .about-hero-image {
      order: -1;
      width: 100%;
    }
  }
  </style>

<main class="about-page">
  <!-- HERO -->
  <section class="about-hero">
    <div class="about-hero-inner">
      <h1>About <span class="brand">KSCandles</span></h1>
      <p class="lead">
        Welcome to <strong>KSCandles</strong> — where warmth meets craftsmanship.  
        We create hand-poured candles that tell a story, set a mood, and fill your space with serenity.  
        Each candle is crafted with care, using premium wax blends and ethically sourced fragrances.
      </p>
      <div class="hero-cta">
        <a href="/kscandles/shop.php" class="btn primary">Explore Collection</a>
        <a href="/kscandles/contact.php" class="btn outline">Get in Touch</a>
      </div>
    </div>

    <div class="about-hero-image">
      <div class="hero-candle">
        <div class="flame">🔥</div>
        <div class="wax"></div>
      </div>
    </div>
  </section>

  <!-- STORY -->
  <section class="about-story">
    <h2>Our Story</h2>
    <p>
      Founded in 2024, <strong>KSCandles</strong> was born from a passion for creating calm in everyday life.  
      What started as a small batch experiment quickly turned into a community of candle lovers who value sustainability, design, and a touch of magic in their homes.
    </p>
    <p>
      We believe in slow living — crafting each candle with precision, testing every scent, and ensuring every flame lights up more than just a room — it brightens moods, memories, and moments.
    </p>
  </section>

  <!-- VALUES -->
  <section class="about-values container">
    <h2>Our Values</h2>
    <div class="values-grid">
      <div class="value-card">
        <h3>🌿 Sustainability</h3>
        <p>We use eco-friendly soy wax, cotton wicks, and recyclable packaging to keep our candles as kind to the planet as they are to you.</p>
      </div>

      <div class="value-card">
        <h3>💎 Quality</h3>
        <p>Each candle undergoes hours of testing to ensure clean burning, long life, and consistent fragrance from start to finish.</p>
      </div>

      <div class="value-card">
        <h3>💖 Handcrafted Care</h3>
        <p>Every candle is poured, labeled, and packed by hand — with love and dedication in every step.</p>
      </div>
    </div>
  </section>

  <!-- TEAM -->
  <section class="about-team container">
    <h2>Meet Our Team</h2>
    <p class="small-lead">The passionate creators behind every KSCandles glow.</p>

    <div class="team-grid">
      <div class="team-member">
        <img src="/kscandles/assets/img/team1.jpg" alt="Harshita Mishra">
        <h4>Harshita Mishra </h4>
        <p>Founder & Scent Curator</p>
      </div>

      <div class="team-member">
        <img src="/kscandles/assets/img/team2.jpg" alt="Stuti Tiwari">
        <h4>Stuti Tiwari</h4>
        <p>Production Lead</p>
      </div>

      <div class="team-member">
        <img src="/kscandles/assets/img/team3.jpg" alt="Ritik Kumar ">
        <h4>Ritik Kumar</h4>
        <p>Technical Lead</p>
      </div>
    </div>
  </section>

  <!-- PROCESS -->
  <section class="about-process container">
    <h2>Our Candle-Making Process</h2>
    <div class="process-grid">
      <div class="process-step"><span>1️⃣</span><p><strong>Selection:</strong> Premium, ethically sourced wax and fragrance oils.</p></div>
      <div class="process-step"><span>2️⃣</span><p><strong>Blending:</strong> Expertly balanced aromas for a perfect scent throw.</p></div>
      <div class="process-step"><span>3️⃣</span><p><strong>Pouring:</strong> Hand-poured in small batches for quality control.</p></div>
      <div class="process-step"><span>4️⃣</span><p><strong>Testing:</strong> Each candle is tested for burn consistency and safety.</p></div>
      <div class="process-step"><span>5️⃣</span><p><strong>Packaging:</strong> Eco-friendly wrapping, ready to gift or enjoy.</p></div>
    </div>
  </section>

  <!-- CALLOUT -->
  <section class="about-callout">
    <div class="container">
      <div class="callout-inner">
        <h3>✨ Experience serenity — get free shipping on orders over ₹799</h3>
        <a href="/kscandles/shop.php" class="btn primary">Shop Bestsellers</a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
