<?php
// ============================================
// SHOP PAGE - product_list.php
// Demonstrates: arrays, foreach loop, dynamic links with GET params, sessions
// ============================================

session_start();

$page_title = "Neon Kactus - Shop";

// Include shared product data
include "parts/products_data.php";

// Filter categories
$filters = ["All", "Indoor", "Low Light", "Pet Friendly", "Easy Care"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?= $page_title ?></title>
<?php include "parts/meta.php"; ?>
</head>

<body>

<?php include "parts/navbar.php"; ?>

<!-- TITLE -->
<section class="sg-section container" style="margin-bottom: 2rem;">
  <p class="label-accent" style="font-style: italic;">curated collection</p>
  <h1>SHOP</h1>
</section>

<!-- FILTERS -->
<section class="container" style="margin-bottom: 2rem;">
  <div class="filter-row">
    <?php foreach ($filters as $index => $filter): ?>
    <button class="pill<?= $index === 0 ? ' active' : '' ?>"><?= $filter ?></button>
    <?php endforeach; ?>
  </div>
</section>

<!-- PRODUCT GRID -->
<section class="sg-section container">
  <div class="grid gap">
    <?php foreach ($products as $id => $product): ?>
    <div class="col-12 col-md-4">
      <a href="product_item.php?id=<?= $id ?>" class="product-card-link">
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            <button class="favorite-btn" onclick="event.preventDefault(); this.classList.toggle('active')">♡</button>
          </div>
          <div class="product-info">
            <h3 class="product-name"><?= $product['name'] ?></h3>
            <div class="display-flex flex-justify-between flex-align-center">
              <p class="product-price">$<?= $product['price'] ?></p>
              <a href="cart_add.php?id=<?= $id ?>&redirect=product_list.php" class="btn-pill btn-sm" onclick="event.stopPropagation();">Add to Cart</a>
            </div>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include "parts/footer.php"; ?>

</body>
</html>
