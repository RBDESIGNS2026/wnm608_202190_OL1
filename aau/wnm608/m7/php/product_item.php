<?php
// ============================================
// PRODUCT DETAIL PAGE - product_item.php
// Demonstrates: $_GET, arrays, conditional output, dynamic content, sessions
// ============================================

session_start();

// Include shared product data
include "parts/products_data.php";

// Get product ID from URL
$id = $_GET['id'] ?? null;
$product = $products[$id] ?? null;

$page_title = $product ? "Neon Kactus - " . $product['name'] : "Neon Kactus - Product Not Found";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
</head>

<body>

<?php include "parts/navbar.php"; ?>

<?php if ($product): ?>

<!-- BREADCRUMBS -->
<div class="container" style="padding-top: 1rem;">
  <nav class="breadcrumbs">
    <a href="index.php">Home</a>
    <span>/</span>
    <a href="product_list.php">Shop</a>
    <span>/</span>
    <span class="accent-gold"><?= $product['name'] ?></span>
  </nav>
</div>

<!-- PRODUCT -->
<section class="sg-section container">
  <p class="label-accent" style="font-style: italic;">product</p>
  <h1><?= strtoupper($product['name']) ?></h1>

  <div class="grid gap" style="margin-top: 3rem;">
    <div class="col-12 col-md-6">
      <div class="card hard" style="padding: 0; overflow: hidden;">
        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" style="width: 100%; height: 500px; object-fit: cover;">
      </div>
    </div>

    <div class="col-12 col-md-6 product-details">
      <p class="label-accent">Premium Selection</p>
      <h2 class="product-title"><?= $product['name'] ?></h2>
      <p class="product-latin"><?= $product['latin'] ?></p>

      <p class="body-text"><?= $product['desc'] ?></p>

      <!-- Care Specs -->
      <div class="care-specs">
        <div class="grid gap">
          <div class="col-4">
            <p class="spec-label">Light</p>
            <p class="spec-value"><?= $product['light'] ?></p>
          </div>
          <div class="col-4">
            <p class="spec-label">Water</p>
            <p class="spec-value"><?= $product['water'] ?></p>
          </div>
          <div class="col-4">
            <p class="spec-label">Care</p>
            <p class="spec-value"><?= $product['care'] ?></p>
          </div>
        </div>
      </div>

      <!-- Price -->
      <div class="price-section">
        <span class="main-price">$<?= $product['price'] ?></span>
        <span class="addon-price">+ Ceramic Planter $45</span>
      </div>

      <!-- CTA -->
      <a href="cart_add.php?id=<?= $id ?>&redirect=checkout.php" class="btn-primary btn-full" style="text-decoration: none; display: block; text-align: center;">Add to Cart</a>

      <p style="margin-top: 1rem; font-size: 0.85rem; color: #777;">
        You are viewing item #<?= $id ?>
      </p>
    </div>
  </div>
</section>

<?php else: ?>

<!-- PRODUCT NOT FOUND -->
<section class="sg-section container">
  <h1>Product Not Found</h1>
  <p class="body-text">Sorry, we couldn't find a product with ID "<?= htmlspecialchars($id ?? 'none') ?>".</p>
  <a href="product_list.php" class="btn-pill">Back to Shop</a>
</section>

<?php endif; ?>

<?php include "parts/footer.php"; ?>

</body>
</html>
