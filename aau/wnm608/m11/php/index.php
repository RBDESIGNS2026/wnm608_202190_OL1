<?php
// ============================================
// HOME PAGE - index.php
// Demonstrates: variables, arrays, include, PHP output, sessions
// ============================================

session_start();

$page_title = "Neon Kactus - Home";

// Include shared product data
include "parts/products_data.php";

// Featured products (first 4)
$featured_products = array_slice($products, 0, 4, true);

// Category data array
$categories = [
  ["name" => "Indoor Plants",   "count" => 24, "image" => "../images/monstera.jpg"],
  ["name" => "Outdoor Plants",  "count" => 18, "image" => "../images/fiddle-leaf-fig.jpg"],
  ["name" => "Planters & Pots", "count" => 32, "image" => "../images/snake-plant.jpg"],
];

// Hero slideshow data
$hero_slides = [
  ["image" => "../images/hero-plants.jpg", "subtitle" => "Where Desert Meets Design", "title" => "ELEVATED<br>BOTANICAL<br>DESIGN", "cta" => "Shop Plants"],
  ["image" => "../images/monstera.jpg",    "subtitle" => "Curated Collection",        "title" => "UNCOMMON<br>PLANTS",                "cta" => "View Available"],
  ["image" => "../images/fiddle-leaf-fig.jpg", "subtitle" => "Premium Selection",      "title" => "STATEMENT<br>GREENERY",             "cta" => "Explore"],
  ["image" => "../images/snake-plant.jpg", "subtitle" => "Modern Planters",            "title" => "DESIGNED<br>TO THRIVE",             "cta" => "Shop Now"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
<style>
/* Hero Slideshow */
.hero-slideshow {
  position: relative;
  width: 100%;
  height: 100vh;
  min-height: 600px;
  max-height: 900px;
  overflow: hidden;
}

.hero-slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 0.8s ease-in-out;
}

.hero-slide.active {
  opacity: 1;
}

.hero-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hero-slide .overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%);
}

.hero-nav {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  z-index: 30;
  padding: 1.5rem 0;
}

.hero-nav .site-title,
.hero-nav .top-nav a {
  color: #ffffff;
}

.hero-nav .top-nav a:hover {
  color: rgba(255,255,255,0.7);
}

.hero-content {
  position: absolute;
  inset: 0;
  z-index: 10;
  display: flex;
  align-items: flex-end;
  padding-bottom: 6rem;
}

.hero-content .container {
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
  padding: 0 24px;
}

.hero-text-block {
  max-width: 700px;
  padding-left: 0;
}

.hero-content .label-accent {
  color: rgba(255,255,255,0.7);
  margin-bottom: 0.5rem;
}

.hero-content h1 {
  color: #ffffff;
  font-size: 3rem;
  line-height: 1.05;
  margin-bottom: 2.5rem;
  margin-top: 0;
}

@media (min-width: 768px) {
  .hero-content h1 {
    font-size: 4.5rem;
  }
}

@media (min-width: 1024px) {
  .hero-content h1 {
    font-size: 5.5rem;
  }
}

.hero-cta {
  display: inline-block;
  align-self: flex-start;
  background: #ffffff;
  color: var(--charcoal);
  padding: 1rem 2rem;
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  text-decoration: none;
  transition: background 0.2s ease;
}

.hero-cta:hover {
  background: var(--stone);
}

.hero-dots {
  position: absolute;
  bottom: 2rem;
  right: 2rem;
  z-index: 20;
  display: flex;
  gap: 0.5rem;
}

.hero-dot {
  width: 10px;
  height: 10px;
  background: rgba(255,255,255,0.4);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.hero-dot.active {
  background: #ffffff;
  transform: scale(1.1);
}

.hero-scroll {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  z-index: 20;
  color: rgba(255,255,255,0.6);
  font-size: 1.5rem;
  background: none;
  border: none;
  cursor: pointer;
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateX(-50%) translateY(0); }
  50% { transform: translateX(-50%) translateY(-10px); }
}
</style>
</head>

<body>

<!-- HERO SLIDESHOW -->
<section class="hero-slideshow">
  <!-- Slides -->
  <?php foreach ($hero_slides as $index => $slide): ?>
  <div class="hero-slide<?= $index === 0 ? ' active' : '' ?>" data-slide="<?= $index ?>">
    <img src="<?= $slide['image'] ?>" alt="<?= strip_tags($slide['title']) ?>">
    <div class="overlay"></div>
  </div>
  <?php endforeach; ?>

  <!-- Nav overlay -->
  <header class="hero-nav">
    <div class="container display-flex flex-justify-between flex-align-center">
      <a href="index.php" class="site-title">NEON KACTUS®</a>
      <nav class="top-nav">
        <a href="product_list.php">Store</a>
        <a href="#categories">Categories</a>
        <a href="#about">Info</a>
        <?php
          $cart_items_hero = $_SESSION['cart'] ?? [];
          $cart_count_hero = 0;
          foreach ($cart_items_hero as $item) { $cart_count_hero += $item['qty']; }
        ?>
        <a href="checkout.php">Cart (<?= $cart_count_hero ?>)</a>
        <a href="admin/users.php">Admin</a>
      </nav>
    </div>
  </header>

  <!-- Slide content -->
  <div class="hero-content">
    <div class="container">
      <div class="hero-text-block">
        <p class="label-accent" style="font-style: italic;" id="hero-subtitle"><?= $hero_slides[0]['subtitle'] ?></p>
        <h1 id="hero-title"><?= $hero_slides[0]['title'] ?></h1>
        <a href="product_list.php" class="hero-cta" id="hero-cta"><?= $hero_slides[0]['cta'] ?></a>
      </div>
    </div>
  </div>

  <!-- Dots -->
  <div class="hero-dots">
    <?php foreach ($hero_slides as $index => $slide): ?>
    <button class="hero-dot<?= $index === 0 ? ' active' : '' ?>" onclick="goToSlide(<?= $index ?>)"></button>
    <?php endforeach; ?>
  </div>

  <!-- Scroll indicator -->
  <button class="hero-scroll" onclick="document.getElementById('categories').scrollIntoView({behavior:'smooth'})">▼</button>
</section>

<!-- SHOP BY CATEGORY -->
<section class="sg-section container" id="categories">
  <p class="label-accent" style="font-style: italic;">explore</p>
  <h2>Shop by Category</h2>
  
  <div class="grid gap">
    <?php foreach ($categories as $category): ?>
    <div class="col-12 col-md-4">
      <a href="product_list.php" class="category-card">
        <img src="<?= $category['image'] ?>" alt="<?= $category['name'] ?>">
        <div class="category-overlay">
          <h3><?= $category['name'] ?></h3>
          <p><?= $category['count'] ?> products</p>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="sg-section container">
  <div class="display-flex flex-justify-between flex-align-center" style="margin-bottom: 2rem;">
    <div>
      <p class="label-accent" style="font-style: italic;">curated for you</p>
      <h2>Featured Plants</h2>
    </div>
    <a href="product_list.php" class="view-all-link">View All →</a>
  </div>
  
  <div class="grid gap">
    <?php foreach ($featured_products as $id => $product): ?>
    <div class="col-12 col-md-3">
      <div class="product-card">
        <div class="product-image-wrapper">
          <a href="product_item.php?id=<?= $id ?>"><img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>"></a>
          <button class="favorite-btn" onclick="this.classList.toggle('active')">♡</button>
        </div>
        <div class="product-info">
          <h3 class="product-name"><?= $product['name'] ?></h3>
          <div class="display-flex flex-justify-between flex-align-center">
            <p class="product-price">$<?= $product['price'] ?></p>
            <a href="cart_add.php?id=<?= $id ?>&redirect=index.php" class="btn-pill btn-sm">Add to Cart</a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ABOUT / VALUE PROPS -->
<section class="sg-section container" id="about">
  <div class="card soft">
    <div class="grid gap">
      <div class="col-12 col-md-6">
        <p class="label-accent" style="font-style: italic;">why neon kactus</p>
        <h2>Plants With Purpose</h2>
        <p class="body-text">
          Every plant in our collection is hand-selected for quality, sustainability, and beauty. We partner with local growers to bring you the healthiest specimens.
        </p>
        <div class="grid gap">
          <div class="col-6">
            <p class="value-title">Free Shipping</p>
            <p class="value-desc">Orders over $75</p>
          </div>
          <div class="col-6">
            <p class="value-title">Plant Guarantee</p>
            <p class="value-desc">30-day health promise</p>
          </div>
          <div class="col-6">
            <p class="value-title">Expert Care Tips</p>
            <p class="value-desc">With every purchase</p>
          </div>
          <div class="col-6">
            <p class="value-title">Eco Packaging</p>
            <p class="value-desc">100% recyclable</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <img src="../images/pothos.jpg" alt="Our plants" style="width: 100%; height: 350px; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="sg-section container newsletter-section">
  <div class="newsletter-content">
    <p class="label-accent" style="font-style: italic;">stay in the loop</p>
    <h2 class="newsletter-title">Join the Plant Club</h2>
    <p>Get care tips, new arrivals, and exclusive offers delivered to your inbox.</p>
    <div class="newsletter-form">
      <input type="email" placeholder="Enter your email" class="input-hotdog">
      <button class="btn-pill">Subscribe</button>
    </div>
  </div>
</section>

<?php include "parts/footer.php"; ?>

<!-- SLIDESHOW JAVASCRIPT -->
<script>
// Hero slideshow data from PHP
var slides = <?= json_encode($hero_slides) ?>;
var currentSlide = 0;
var isTransitioning = false;

function goToSlide(index) {
  if (isTransitioning || index === currentSlide) return;
  isTransitioning = true;

  // Update slide backgrounds
  var slideEls = document.querySelectorAll('.hero-slide');
  var dotEls = document.querySelectorAll('.hero-dot');

  slideEls[currentSlide].classList.remove('active');
  dotEls[currentSlide].classList.remove('active');

  slideEls[index].classList.add('active');
  dotEls[index].classList.add('active');

  // Update text content
  document.getElementById('hero-subtitle').textContent = slides[index].subtitle;
  document.getElementById('hero-title').innerHTML = slides[index].title;
  document.getElementById('hero-cta').textContent = slides[index].cta;

  currentSlide = index;

  setTimeout(function() {
    isTransitioning = false;
  }, 800);
}

// Auto-advance every 5 seconds
setInterval(function() {
  var next = (currentSlide + 1) % slides.length;
  goToSlide(next);
}, 5000);
</script>

</body>
</html>
